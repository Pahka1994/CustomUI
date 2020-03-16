<?php

namespace CustomUI;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;
use pocketmine\Player;
use pocketmine\Server;

use CustomUI\Form\SimpleForm;
use CustomUI\Form\CustomForm;
use CustomUI\Form\LongForm;
use CustomUI\Form\ModalForm;

class CustomUI extends PluginBase implements Listener
{

	public $formCount = 0;
	public $forms = [];
	public $playerName;
	public $isForm = [];

	private static $instance = null;

	public function onLoad()
	{
		self::$instance = $this;
	}

	public static function runFunction()
	{
		return self::$instance;
	}

	public function onEnable()
	{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function ModalForm(callable $function = null)
	{
		$this->formCount++;
		$form = new ModalForm($this->formCount, $function);
		$this->forms[$this->formCount] = $form;
		return $form;
	}

	public function SimpleForm(callable $function = null)
	{
		$this->formCount++;
		$form = new SimpleForm($this->formCount, $function);
		$this->forms[$this->formCount] = $form;
		return $form;
	}

	public function CustomForm(callable $function = null)
	{
		$this->formCount++;
		$form = new CustomForm($this->formCount, $function);
		$this->forms[$this->formCount] = $form;
		return $form;
	}

	public function LongForm(callable $function = null)
	{
		$this->formCount++;
		$form = new LongForm($this->formCount, $function);
		$this->forms[$this->formCount] = $form;
		return $form;
	}

	public function onPacketReceive(DataPacketReceiveEvent $ev)
	{
		$pk = $ev->getPacket();
		if ($pk instanceof ModalFormResponsePacket) {
			$player = $ev->getPlayer();
			unset($this->isForm[$player->getName()]);
			$formId = $pk->formId;
			$data = json_decode($pk->formData, true);
			if (isset($this->forms[$formId])) {
				$form = $this->forms[$formId];
				if (!$form->isRecipient($player)) {
					return;
				}
				$callable = $form->getCallable();
				if (!is_array($data)) {
					$data = [$data];
				}
				if ($callable !== null) {
					$callable($ev->getPlayer(), $data);
				}
				unset($this->forms[$formId]);
				$ev->setCancelled(true);
			}
		}
	}

	public function onMove(PlayerMoveEvent $ev)
	{
		$player = $ev->getPlayer();
		if (isset($this->isForm[$player->getName()]))
			unset($this->isForm[$player->getName()]);
	}

	public function onQuit(PlayerQuitEvent $ev)
	{
		$player = $ev->getPlayer();
		if (isset($this->isForm[$player->getName()]))
			unset($this->isForm[$player->getName()]);
	}
}
