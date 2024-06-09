<?php

namespace App\Data\DataTransferObject;

use App\Data\Entity\Team;

class TeamDTO
{
    public int $id;
    public string $name;
    public float $moneyBalance;
    public \DateTime $createdAt;
    public \DateTime $updatedAt;

    public function __construct(Team $team)
    {
        $this->id = $team->getId();
        $this->name = $team->getName();
        $this->moneyBalance = $team->getMoneyBalance();
        $this->createdAt = $team->getCreatedAt();
        $this->updatedAt = $team->getUpdatedAt();
    }
}