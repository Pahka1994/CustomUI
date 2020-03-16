<?php

namespace CustomUI\Form;

use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\Player;
use CustomUI\CustomUI;

class ModalForm extends Library
{

	public $id;
	private $data = [];
	private $title = "";
	private $content = "";
	public $playerName;

	public function __construct(int $id, callable $function)
	{
		parent::__construct($id, $function);
		$this->data["type"] = "modal";
		$this->data["title"] = $this->title;
		$this->data["content"] = $this->content;
		$this->data["button1"] = "";
		$this->data["button2"] = "";
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

	public function setButton1(string $text)
	{
		$this->data["button1"] = $text;
	}

	public function getButton1()
	{
		return $this->data["button1"];
	}

	public function setButton2(string $text)
	{
		$this->data["button2"] = $text;
	}

	public function getButton2()
	{
		return $this->data["button2"];
	}
}
