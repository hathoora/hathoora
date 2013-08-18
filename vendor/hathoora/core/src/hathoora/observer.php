<?php
namespace hathoora
{
    use hathoora\logger\logger,
        hathoora\configure\config;

    class observer
    {
        /**
         * array of all availabe events
         */
        private $arrEvents;

        /**
         * Add an event
         *
         * @param string $eventName
         * @param mixed $subject that notifies observers and is passed as argument to observers
         * @param bool $returnResult when true, the result of observer will be send back
         * @return mixed
         */
        public function addEvent($eventName, &$subject, $returnResult = false)
        {
            $this->arrEvents[$eventName]['name'] = $eventName;
            $this->arrEvents[$eventName]['subject'] =& $subject;

            logger::log(logger::LEVEL_DEBUG, 'Event ' . $eventName .' has been registered.');

            if (isset($this->arrEvents[$eventName]['observers']) && count($this->arrEvents[$eventName]['observers']))
            {
                foreach($this->arrEvents[$eventName]['observers'] as $observerName => $observer)
                {
                    $class = $method = null;
                    if (is_array($observer) && isset($observer['class']))
                    {
                        $class = $observer['class'];
                        if (isset($observer['method']))
                            $method = $observer['method'];
                    }

                    // @todo check file before loading..
                    #sqlLoadClassTest(true);
                    if (is_callable(array($class, $method)))
                    {
                        #sqlLoadClassTest(false);
                        logger::log(logger::LEVEL_DEBUG, 'Event ' . $eventName .'->' . $observerName .' has been notified.');
                        $classObj = new $class();
                        $result = $classObj->$method($subject);

                        if ($returnResult)
                            return $result;
                    }
                    else
                        logger::log(logger::LEVEL_DEBUG, 'Event ' . $eventName .'->' . $observerName .'->'. $method .' is not callable.');
                    #sqlLoadClassTest(false);
                }
            }
        }

        /**
         * add a listener
         *
         * @param string $eventName
         * @param string $observerName for this event
         * @param array $observer that does something to object
         */
        public function addListener($eventName, $observerName, $observer)
        {
            if (is_array($observer) && isset($observer['class']))
            {
                $class = $observer['class'];
                $observer = $observer;
            }
            // @todo: is_object()

            if (isset($class))
                $this->arrEvents[$eventName]['observers'][$observerName] = $observer;
            // @todo log it
        }

        /**
         * Add listeners defined in config so that can be notified when events are triggered
         */
        public function addEventListenersFromConfig()
        {
            $arrListeners = config::get('listeners');

            // add webprofiler listener
            if (config::get('hathoora.logger.webprofiler.enabled'))
            {
                $arrListeners['kernel.terminate']['webprofiler'] = array(
                                                                            'class' => '\hathoora\logger\webprofiler\webprofiler',
                                                                            'method' => 'display');

                logger::log(logger::LEVEL_DEBUG, 'Listener "kernel.terminate[webprofiler]" has been added because <i>hathoora.logger.webprofiler</i> is enabled.');
            }

            if (is_array($arrListeners))
            {
                foreach($arrListeners as $eventName => $arrObservers)
                {
                    foreach($arrObservers as $observerName => $arrObserver)
                    {
                        $this->addListener($eventName, $observerName, $arrObserver);
                    }
                }
            }
        }
    }
}