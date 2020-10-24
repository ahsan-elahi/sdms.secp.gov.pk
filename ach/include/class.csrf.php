<?php

Class CSRF {

    var $name;
    var $timeout;

    var $csrf;

    function CSRF($name='__CSRFToken__', $timeout=0) {

        $this->name = $name;
        $this->timeout = $timeout;
        $this->csrf = &$_SESSION['csrf'];
    }

    function reset() {
        $this->csrf = array();
    }

    function isExpired() {
       return ($this->timeout && (time()-$this->csrf['time'])>$this->timeout);
    }

    function getTokenName() {
        return $this->name;
    }

    function getToken($len=32) {

        if(!$this->csrf['token'] || $this->isExpired()) {

            $len = $len>8?$len:32;
            $r = '';
            for ($i = 0; $i <= $len; $i++)
                $r .= chr(mt_rand(0, 255));
        
            $this->csrf['token'] = base64_encode(sha1(session_id().$r.SECRET_SALT));
            $this->csrf['time'] = time();
        } else {
            //Reset the timer
            $this->csrf['time'] = time();
        }

        return $this->csrf['token'];
    }

    function validateToken($token) {
        return ($token && trim($token)==$this->getToken() && !$this->isExpired());
    }

    function getFormInput($name='') {
        if(!$name) $name = $this->name;

        return sprintf('<input type="hidden" name="%s" value="%s" />', $name, $this->getToken());
    }
}

/* global function to add hidden token input with to forms */
function csrf_token() {
    global $ost;

    if($ost && $ost->getCSRF())
        echo $ost->getCSRFFormInput();
}
?>
