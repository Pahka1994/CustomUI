<?php

namespace CustomUI\Form;

use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\Player;
use CustomUI\CustomUI;

class LongForm extends Library
{

	public $id;
	private $data = [];
	private $title = "";
	private $content = "";
	public $playerName;

	public function __construct(int $id, callable $function)
	{
		parent::__construct($id, $function);
		$this->data["type"] = "long_form";
		$this->data["title"] = $this->title;
		$this->data["content"] = $this->content;
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

	public function setTitle(string $title)
	{
		$this->data["title"] = $title;
	}

	public function getTitle()
	{
		return $this->data["title"];
	}

	public function getContent()
	{
		return $this->data["content"];
	}

	public function setContent(string $content)
	{
		$this->data["content"] = $content;
	}
}
