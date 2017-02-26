<?php

namespace Botter;

use Botter\Exception\InvalidUpdateTypeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;

class BotRouteMatcher extends UrlMatcher
{
    const TYPE_MESSAGE = "message";
    const TYPE_INLINE_QUERY = "inline_query";
    const TYPE_CALLBACK_QUERY = "callback_query";

    /**
     * Tries to match a request with a set of routes.
     *
     * If the matcher can not find information, it must throw one of the exceptions documented
     * below.
     *
     * @param Request $request The request to match
     *
     * @return array An array of parameters
     *
     * @throws ResourceNotFoundException If no matching resource could be found
     * @throws MethodNotAllowedException If a matching resource was found but the request method is not allowed
     */
    public function matchRequest(Request $request) {
        $data = json_decode($request->getContent(), true);
        try {
            $attributes = [];
            $attributes['update_type'] = $this->getUpdateType($data);
            $attributes['value'] = $this->getValue($attributes['update_type'], $data);
        } catch (InvalidUpdateTypeException $exception) {
            throw new ResourceNotFoundException();
        }
        $pathinfo = '/'.implode('/', $attributes);

        $this->request = $request;

        $ret = $this->match($pathinfo);

        $this->request = null;

        return $ret;
    }

    /**
     * @param array $data
     * @return string
     * @throws \Botter\Exception\InvalidUpdateTypeException
     */
    public function getUpdateType(array $data)
    {
        if (isset($data[self::TYPE_MESSAGE])) {
            return self::TYPE_MESSAGE;
        } elseif (isset($data[self::TYPE_INLINE_QUERY])) {
            return self::TYPE_INLINE_QUERY;
        } elseif (isset($data[self::TYPE_CALLBACK_QUERY])) {
            return self::TYPE_CALLBACK_QUERY;
        }

        throw new InvalidUpdateTypeException();
    }

    /**
     * @param $updateType
     * @param $data
     * @return mixed
     * @throws \Botter\Exception\InvalidUpdateTypeException
     */
    private function getValue($updateType, $data)
    {
        switch ($updateType) {
            case self::TYPE_MESSAGE:
                return $data[self::TYPE_MESSAGE]['text'];
                break;
            case self::TYPE_INLINE_QUERY:
                return $data[self::TYPE_INLINE_QUERY]['query'];
                break;
            case self::TYPE_CALLBACK_QUERY:
                return $data[self::TYPE_CALLBACK_QUERY]['data'];
                break;
            default:
                throw new InvalidUpdateTypeException();
                break;
        }
    }
}