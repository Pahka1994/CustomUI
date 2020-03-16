<?php

namespace CustomUI\Form;

use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\Player;
use CustomUI\CustomUI;

class CustomForm extends Library
{

	public $id;
	private $data = [];
	public $playerName;
	private $labelMap = [];

	public function __construct(int $id, callable $function)
	{
		parent::__construct($id, $function);
		$this->data["type"] = "custom_form";
		$this->data["title"] = "";
		$this->data["content"] = [];
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

	public function processData(&$data)
	{
		if (is_array($data)) {
			$new = [];
			foreach ($data as $i => $v) {
				$new[$this->labelMap[$i]] = $v;
			}
			$data = $new;
		}
	}

	public function setTitle(string $title)
	{
		$this->data["title"] = $title;
	}

	public function getTitle()
	{
		return $this->data["title"];
	}

	public function addLabel(string $text, ?string $label = null)
	{
		$this->addContent(["type" => "label", "text" => $text]);
		$this->labelMap[] = $label ?? count($this->labelMap);
	}

	public function addToggle(string $text, bool $default = null, ?string $label = null)
	{
		$content = ["type" => "toggle", "text" => $text];
		if ($default !== null) {
			$content["default"] = $default;
		}
		$this->addContent($content);
		$this->labelMap[] = $label ?? count($this->labelMap);
	}

	public function addSlider(string $text, int $min, int $max, int $step = -1, int $default = -1, ?string $label = null)
	{
		$content = ["type" => "slider", "text" => $text, "min" => $min, "max" => $max];
		if ($step !== -1) {
			$content["step"] = $step;
		}
		if ($default !== -1) {
			$content["default"] = $default;
		}
		$this->addContent($content);
		$this->labelMap[] = $label ?? count($this->labelMap);
	}

	public function addStepSlider(string $text, array $steps, int $defaultIndex = -1, ?string $label = null)
	{
		$content = ["type" => "step_slider", "text" => $text, "steps" => $steps];
		if ($defaultIndex !== -1) {
			$content["default"] = $defaultIndex;
		}
		$this->addContent($content);
		$this->labelMap[] = $label ?? count($this->labelMap);
	}

	public function addDropdown(string $text, array $options, int $default = null, ?string $label = null): void
	{
		$this->addContent(["type" => "dropdown", "text" => $text, "options" => $options, "default" => $default]);
		$this->labelMap[] = $label ?? count($this->labelMap);
	}

	public function addInput(string $text, string $placeholder = "", string $default = null, ?string $label = null): void
	{
		$this->addContent(["type" => "input", "text" => $text, "placeholder" => $placeholder, "default" => $default]);
		$this->labelMap[] = $label ?? count($this->labelMap);
	}

	private function addContent(array $content): void
	{
		$this->data["content"][] = $content;
	}

}
