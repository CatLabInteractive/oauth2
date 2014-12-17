<?php
/**
 * Created by PhpStorm.
 * User: daedeloth
 * Date: 17/12/14
 * Time: 15:48
 */

namespace CatLab\OAuth2\Controllers;

use Neuron\Exceptions\InvalidParameter;
use Neuron\Interfaces\Controller;
use Neuron\Interfaces\Module;
use Neuron\Net\Request;

abstract class Base
	implements Controller
{

	/** @var Module $module */
	protected $module;

	/** @var  Request $request */
	protected $request;

	/**
	 * Controllers must know what module they are from.
	 * @param \Neuron\Interfaces\Module $module
	 * @throws InvalidParameter
	 */
	public function __construct (\Neuron\Interfaces\Module $module = null)
	{
		if (! ($module instanceof Module))
		{
			throw new InvalidParameter ("Controller must be instanciated with a \\CatLab\\OAuth\\Module. Instance of " . get_class ($module) . " given.");
		}

		$this->module = $module;
	}

	/**
	 * Set (or clear) the request object.
	 * @param Request $request
	 * @return void
	 */
	public function setRequest (Request $request = null)
	{
		$this->request = $request;
	}
}