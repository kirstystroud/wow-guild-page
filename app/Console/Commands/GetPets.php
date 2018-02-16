<?php

namespace App\Console\Commands;

use Character;
use CharacterPet;
use Pet;
use PetType;
use BlizzardApi;
use Illuminate\Console\Command;

use Log;

class GetPets extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:pets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make api requests to update character pets';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {

        $characters = Character::all();

        if (!$characters) {
            Log::error('No characters found, please run get:characters');
            exit(1);
        }

        Log::debug('Updating character pets');

        $progressBar = $this->output->createProgressBar(count($characters));

        foreach($characters as $char) {
            $this->updatePets($char);
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->line('');
    }

    protected function updatePets($character) {
        $characterPets = BlizzardApi::getPets($character);
        if (!$characterPets) return false;  // failure from API

        Log::info('Checking ' . count($characterPets['pets']['collected']) . ' pets for ' . $character->name);

        foreach($characterPets['pets']['collected'] as $data) {

            $speciesIdExt = $data['stats']['speciesId'];

            // Do we have an entry for this in our pets table
            $pet = Pet::where('id_ext', $speciesIdExt)->first();
            if (!$pet) {
                $petData = BlizzardApi::getPetSpecies($speciesIdExt);
                if (!$petData) continue;        // failure from API

                $pet = new Pet;
                $pet->id_ext = $speciesIdExt;
                $pet->name = $petData['name'];

                if (strlen($petData['description']) <= 250) {
                    $pet->description = $petData['description'];
                } else {
                    $pet->description = substr($petData['description'], 0, 250) . ' ...';
                }

                if (strlen($petData['source']) <= 250) {
                    $pet->source = $petData['source'];
                } else {
                    $pet->source = substr($petData['source'], 0, 250) . ' ...';
                }

                $petType = PetType::where('id_ext', $petData['petTypeId'])->first();
                $pet->type = $petType->id;
                $pet->save();

                Log::debug('Found new pet ' . $pet->name);
            }

            // Do we have an entry for this in our character pets table
            $link = CharacterPet::where('id_ext', $data['battlePetGuid'])
                    ->where('character_id', $character->id)
                    ->where('pet_id', $pet->id)
                    ->first();
            if (!$link) {
                $link = new CharacterPet;
                $link->id_ext = $data['battlePetGuid'];
                $link->character_id = $character->id;
                $link->pet_id = $pet->id;
                Log::debug($character->name . ' has a new pet ' . $pet->name); 
            }

            if ($link->name != $data['name']) {
                $link->name = $data['name'];
                if ($link->name != $pet->name) {
                    Log::debug($character->name . '\'s pet ' . $pet->name . ' has been renamed to ' . $link->name);
                }
            }

            if ($link->level != $data['stats']['level']) {
                $link->level = $data['stats']['level'];
                Log::debug($character->name . '\'s pet ' . $link->name . ' is now level ' . $link->level );
            }

            if ($link->quality != $data['stats']['petQualityId']) {
                $link->quality = $data['stats']['petQualityId'];
            }

            $link->is_favourite = $data['isFavorite'] ? CharacterPet::FAVOURITE : CharacterPet::NOT_FAVOURITE;
            $link->save();
        }
        
    }
}
