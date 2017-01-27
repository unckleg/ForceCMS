<?php

namespace ForceCMS\Controller;

/**
 * Enchanced ControllerAction
 *
 * Each argument must have @param DocBlock
 * Order of @param DocBlocks *is* important
 *
 * Allows to inject object dependency on actions:
 * @example
 *   * @param int $pageid
 *   * @param Default_Form_Test $form
 *   public function indexAction($pageid, Default_Form_Test $form)
 *
 * @package     ForceCMS
 * @subpackage  Controller
 * @category    Dispatch
 * @copyright   Copyright (c) 20012-2017 Djordje Stojiljkovic <djordjestojilljkovic@gmail.com>
 */
abstract class Controller_Abstract extends Zend_Controller_Action
{
    /**
     *
     * @var array
     */
    protected $_basicTypes = [
        'int',     'integer', 'bool',
        'boolean', 'string',  'array',
        'object',  'double',  'float'
    ];

    /**
     * Detect whether dispatched action exists
     *
     * @param string $action
     * @return bool
     */
    protected function _hasAction($action)
    {
        if ($this->getInvokeArg('useCaseSensitiveActions')) {
            trigger_error(
                'Using case sensitive actions without word separators' .
                'is deprecated; please do not rely on this "feature"'
            );

            return true;
        }

        if (method_exists($this, $action)) {
            return true;
        }

        return false;
    }

    /**
     *
     * @param string $action
     * @return array of Zend_Reflection_Parameter objects
     */
    protected function _actionReflectionParams($action)
    {
        $reflMethod = new Zend_Reflection_Method($this, $action);
        $parameters = $reflMethod->getParameters();

        return $parameters;
    }

    /**
     *
     * @param Zend_Reflection_Parameter $parameter
     * @return string
     * @throws Exception when required @param is missing
     */
    protected function _getParameterType(Zend_Reflection_Parameter $parameter)
    {
        // get parameter type
        $reflClass = $parameter->getClass();

        if ($reflClass instanceof Zend_Reflection_Class) {
            $type = $reflClass->getName();
        } else if ($parameter->isArray()) {
            $type = 'array';
        } else {
            $type = $parameter->getType();
        }

        if (null === $type) {
            throw new Exception(
                sprintf(
                    "Required @param DocBlock not found for '%s'", $parameter->getName()
                )
            );
        }

        return $type;
    }

    /**
     *
     * @param Zend_Reflection_Parameter $parameter
     * @return mixed
     * @throws Exception when required argument is missing
     */
    protected function _getParameterValue(Zend_Reflection_Parameter $parameter)
    {
        $name = $parameter->getName();
        $requestValue = $this->getRequest()->getParam($name);

        if (null !== $requestValue) {
            $value = $requestValue;
        } else if ($parameter->isDefaultValueAvailable()) {
            $value = $parameter->getDefaultValue();
        } else {
            if (!$parameter->isOptional()) {
                $value = null;
            }

            $value = null;
        }

        return $value;
    }

    /**
     * @param $value
     * @param $type
     * @return mixed
     */
    protected function _fixValueType($value, $type)
    {
        if (in_array($type, $this->_basicTypes)) {
            settype($value, $type);
        }

        return $value;
    }

    /**
     * Dispatch the requested action
     *
     * @param   string $action Method name of action
     * @return  void
     */
    public function dispatch($action)
    {
        $request = $this->getRequest();

        // Notify helpers of action preDispatch state
        $this->_helper->notifyPreDispatch();

        $this->preDispatch();
        if ($request->isDispatched()) {

            // preDispatch() didn't change the action, so we can continue
            if ($this->_hasAction($action)) {

                $requestArgs = [];
                $dependencyObjects = [];
                $requiredArgs = [];

                foreach ($this->_actionReflectionParams($action) as $parameter) {
                    $type = $this->_getParameterType($parameter);
                    $name = $parameter->getName();
                    $value = $this->_getParameterValue($parameter);

                    if (!in_array($type, $this->_basicTypes)) {

                        if (!is_object($value)) {
                            $value = new $type($value);
                        }

                        $dependencyObjects[$name] = $value;

                    } else {
                        $value = $this->_fixValueType($value, $type);
                        $requestArgs[$name] = $value;
                    }

                    if (!$parameter->isOptional()) {
                        $requiredArgs[$name] = $value;
                    }
                }

                // handle canonical URLs here
                $allArgs = array_merge($requestArgs, $dependencyObjects);

                // dispatch the action with arguments
                call_user_func_array([$this, $action], $allArgs);

            } else {
                $this->__call($action, []);
            }

            $this->postDispatch();
        }

        $this->_helper->notifyPostDispatch();
    }
}
