<?php namespace iHerb;

class Collection implements \Iterator {
    protected $position = 0;
    protected $items    = [];
    public function count() {
        return count($this->items);
    }

    public function push(array $item) {
        $this->items[] = $item;
    }

    public function toArray() {
        return $this->items;
    }

    public function current() {
        $this->items[$this->position];
    }

    public function next() {
        ++$this->position;
    }

    public function key() {
        $this->position;
    }

    public function valid() {
        return isset($this->items[$this->position]);
    }

    public function rewind() {
        $this->position = 0;
    }
}