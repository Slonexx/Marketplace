<?php

class AppInstanceContoller {

    const UNKNOWN = 0;
    const SETTINGS_REQUIRED = 1;
    const ACTIVATED = 100;

    var $appId;
    var $accountId;
    var $infoMessage;
    var $store;

    var $accessToken;

    var $status = AppInstanceContoller::UNKNOWN;

    static function get(): AppInstanceContoller {
        $app = $GLOBALS['currentAppInstance'];
        if (!$app) {
            throw new InvalidArgumentException("There is no current app instance context");
        }
        return $app;
    }

    public function __construct($appId, $accountId)
    {
        $this->appId = $appId;
        $this->accountId = $accountId;
    }

    function getStatusName() {
        switch ($this->status) {
            case self::SETTINGS_REQUIRED:
                return 'SettingsRequired';
            case self::ACTIVATED:
                return 'Activated';
        }
        return null;
    }

    function persist() {
        @mkdir('data');
        file_put_contents($this->filename(), serialize($this));
    }

    function delete() {
        @unlink($this->filename());
    }

    function deleteAnway(){
        $url = 'https://smartkaspi.kz/api/DeleteVendorApi/'.$this->appId.'/'.$this->accountId;
        $result = file_get_contents($url);
        $this->loginfo('Удлаение', $result);
    }

    private function filename() {
        return self::buildFilename($this->appId, $this->accountId);
    }

    private static function buildFilename($appId, $accountId) {
        return $GLOBALS['dirRoot'] . "data/$appId.$accountId.json";
    }

    static function loadApp($accountId): AppInstanceContoller {
        return self::load(cfg()->appId, $accountId);
    }

    static function load($appId, $accountId): AppInstanceContoller {
        $data = @file_get_contents(self::buildFilename($appId, $accountId));
        if ($data === false) {
            $app = new AppInstanceContoller($appId, $accountId);
        } else {
            $unser = json_encode( unserialize($data) );
            $app =  json_decode($unser);
        }

        $AppInstance = new AppInstanceContoller($app->appId, $app->accountId);
        $AppInstance->Pasrs($app);

        $GLOBALS['currentAppInstance'] = $AppInstance;
        return $AppInstance;
    }

    public function Pasrs($json){
        $this->appId = $json->appId;
        $this->accountId = $json->accountId;
        $this->infoMessage = $json->infoMessage;
        $this->store = $json->store;
        $this->accessToken = $json->accessToken;
        $this->status = $json->status;
    }

    function loginfo($name, $msg) {
        global $dirRoot;
        $logDir = $dirRoot . 'logs';
        @mkdir($logDir);
        file_put_contents($logDir . '/log.txt', date(DATE_W3C) . ' [' . $name . '] '. $msg . "\n", FILE_APPEND);
    }


}
