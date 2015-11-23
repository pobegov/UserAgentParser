<?php
namespace UserAgentParser\Provider;

use UserAgentParser\Exception;
use UserAgentParser\Model;

class DonatjUAParser extends AbstractProvider
{
    public function getName()
    {
        return 'DonatjUAParser';
    }

    public function getComposerPackageName()
    {
        return 'donatj/phpuseragentparser';
    }

    /**
     *
     * @param array $resultRaw
     *
     * @return bool
     */
    private function hasResult(array $resultRaw)
    {
        if ($resultRaw['browser'] !== null) {
            return true;
        }

        return false;
    }

    public function parse($userAgent, array $headers = [])
    {
        $resultRaw = parse_user_agent($userAgent);

        if ($this->hasResult($resultRaw) !== true) {
            throw new Exception\NoResultFoundException('No result found for user agent: ' . $userAgent);
        }

        /*
         * Hydrate the model
         */
        $result = new Model\UserAgent();
        $result->setProviderResultRaw($resultRaw);

        /*
         * Bot detection - is currently not possible!
         */

        /*
         * Browser
         */
        $browser = $result->getBrowser();

        if ($this->isRealResult($resultRaw['browser']) === true) {
            $browser->setName($resultRaw['browser']);
        }

        if ($this->isRealResult($resultRaw['version']) === true) {
            $browser->getVersion()->setComplete($resultRaw['version']);
        }

        /*
         * operatingSystem
         *
         * @todo $resultRaw['platform'] has sometimes informations about the OS or the device
         * ... maybe split it or how do that?
         */

        return $result;
    }
}
