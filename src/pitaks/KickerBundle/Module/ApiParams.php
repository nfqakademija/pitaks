<?php

namespace pitaks\KickerBundle\Module;

class ApiParams
{
    /**
     * @var string
     */
    protected $url = '';
    /**
     * @var array
     */
    protected $params = [];
    /**
     * @var array
     */
    protected $auth = [];

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function setParam($name, $value)
    {
        $this->params[$name] = $value;

        return $this;
    }

    public function getParam($name)
    {
        if (array_key_exists($name, $this->params)) {
            return $this->params[$name];
        }

        return null;
    }

    public function removeParam($name)
    {
        if (array_key_exists($name, $this->params)) {
            unset($this->params[$name]);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     * @param array $auth
     * @return $this
     */
    public function setAuth($auth)
    {
        $this->auth = $auth;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getQueryString()
    {
        return $this->url . '' . http_build_query($this->params);

    }
}