<?php

require_once __DIR__ . '/apisearch_client.php';
require_once __DIR__ . '/apisearch_defaults.php';

/**
 * Class Connection
 */
class ApisearchConnection
{
    private $currentClient;

    /**
     * @return ApisearchClient|false
     */
    public function getCurrentConnection()
    {
        if (!$this->currentClient instanceof ApisearchClient) {
            $this->currentClient = $this->getConnectionByLanguageId();
        }

        return $this->currentClient;
    }

    /**
     * @return bool
     */
    public function isProperlyConfigured()
    {
        return $this->getCurrentConnection() !== false;
    }

    /**
     * @param string $langId
     *
     * @return ApisearchClient|false
     */
    public function getConnectionByLanguageId($langId = '')
    {
        if (empty($langId)) {
            $langId = Context::getContext()->language->id;
        }

        $clusterUrl = Configuration::get('AS_CLUSTER_URL');
        $clusterUrl = $clusterUrl == "" ? ApisearchDefaults::DEFAULT_AS_CLUSTER_URL : $clusterUrl;
        $apiVersion = Configuration::get('AS_API_VERSION');
        $apiVersion = $apiVersion == "" ? ApisearchDefaults::DEFAULT_AS_API_VERSION : $apiVersion;


        $appId = Configuration::get('AS_APP');
        $indexId = Configuration::get('AS_INDEX', $langId);
        $token = Configuration::get('AS_TOKEN', $langId);

        if (empty($appId) || empty($indexId) || empty($token)) {
            return false;
        }

        $apisearchClient = new ApisearchClient($clusterUrl, $apiVersion);
        $apisearchClient->setCredentials($appId, $indexId, $token);

        return $apisearchClient;
    }
}
