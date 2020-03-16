<?php

namespace CustomUI\Form;

use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\Player;
use CustomUI\CustomUI;

abstract class Library
{

	public $id;
	private $data = [];
	public $playerName;
	private $callable;

	public function __construct(int $id, callable $function)
	{
		$this->id = $id;
		$this->function = $function;
	}

	public function getId()
	{
		return $this->id;
	}

	public function sendToPlayer(Player $player)
	{
		if (isset(CustomUI::runFunction()->isForm[$player->getName()]))
			return false;
		$pk = new ModalFormRequestPacket();
		$pk->formId = $this->id;
		$pk->formData = json_encode($this->data);
		$player->dataPacket($pk);
		$this->playerName = $player->getName();
		CustomUI::runFunction()->isForm[$player->getName()] = true;
		return true;
	}

	public function isRecipient(Player $player)
	{
		return $player->getName() === $this->playerName;
	}

	public function getCallable()
	{
		return $this->function;
	}

}
