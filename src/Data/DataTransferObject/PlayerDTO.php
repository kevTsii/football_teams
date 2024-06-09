<?php

namespace App\Data\DataTransferObject;

use App\Data\Entity\Player;

class PlayerDTO
{
    public int $id;
    public string $name;
    public string $surname;
    public \DateTime $createdAt;
    public \DateTime $updatedAt;

    public function __construct(Player $player)
    {
        $this->id = $player->getId();
        $this->name = $player->getName();
        $this->surname = $player->getSurname();
        $this->createdAt = $player->getCreatedAt();
        $this->updatedAt = $player->getUpdatedAt();
    }
}