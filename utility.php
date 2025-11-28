<?php

function _make($method, $path, $callback)
{
    if ($_SERVER["REQUEST_METHOD"] == $method && preg_match("/^\/info306\/(info_|spi_)?(gr[0-9]+|api\/v1)([^?]*)(\?.*)?$/", $_SERVER["REQUEST_URI"], $out)) {
        $source = explode("/", $out[3]);
        $target = explode("/", $path);
        if (count($source) == count($target)) {
            $i = 1;
            $error = false;
            $param = [];
            while ($i < count($source) && !$error) {
                if (!empty($source[$i]) && $target[$i][0] == ":") {
                    $param[ltrim($target[$i], ':')] = $source[$i];
                } else {
                    $error = $source[$i] != $target[$i];
                }
                $i++;
            }
            if (!$error) {
                echo json_encode($callback($param));
                exit;
            }
        }
    }
}

function get($path, $callback)
{
    _make("GET", $path, $callback);
}

function options($path, $callback)
{
    _make("OPTIONS", $path, $callback);
}

function post($path, $callback)
{
    _make("POST", $path, $callback);
}

function put($path, $callback)
{
    options($path, function () {
        return ['status' => 'ok'];
    });
    _make("PUT", $path, $callback);
}

function delete($path, $callback)
{
    options($path, function () {
        return ['status' => 'ok'];
    });
    _make("DELETE", $path, $callback);
}

function read_put()
{
    parse_str(file_get_contents("php://input"), $_PUT);
    return $_PUT;
}

function read_delete()
{
    parse_str(file_get_contents("php://input"), $_DELETE);
    return $_DELETE;
}
