<?php

class ErrorHandler {
    private $err = array();

    private function setErrorArray($err, $code = 0) {
        return array(
            "code"          => $code,
            "menssage"      => $err,
            "file"          => __FILE__,
            "line"          => "",
            "previous"      => "",
            "trace"         => "",
            "traceAsString" => "Class Error",
        );
    }

    public function try ($callback) {
        if (is_callable($callback)) {
            try {
                $callback();
            } catch (Exception $e) {
                $this->err[] = array(
                    "code"          => $e->getCode(),
                    "menssage"      => $e->getMessage(),
                    "file"          => $e->getFile(),
                    "line"          => $e->getLine(),
                    "previous"      => $e->getPrevious(),
                    "trace"         => $e->getTrace(),
                    "traceAsString" => $e->getTraceAsString(),
                );
            }
        } else {
            $this->err[] = $this->setErrorArray("O parâmetro informado no TRY não é uma função executável.");
        }
        return $this;
    }

    public function catch ($callback) {
        if (is_callable($callback)) {
            $callback($this->err);
        } else {
            $this->err = $this->setErrorArray("O parâmetro informado no CATCH não é uma função executável.");
        }
        return $this;
    }

    public function finally ($callback) {
        if (is_callable($callback)) {
            $callback();
        } else {
            $this->err = $this->setErrorArray("O parâmetro informado no FINALLY não é uma função executável.");
        }
        return $this;
    }

}

