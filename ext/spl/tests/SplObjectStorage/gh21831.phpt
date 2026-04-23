--TEST--
GH-21831: SplObjectStorage::removeAllExcept() tolerates re-entrant deletion in getHash()
--FILE--
<?php

class FilterStorage extends SplObjectStorage {
    public ?SplObjectStorage $other = null;

    public function getHash($obj): string {
        if ($this->other) {
            $this->other->offsetUnset($obj);
            $this->other = null;
        }

        return 'x';
    }
}

$storage = new SplObjectStorage();
$storage[new stdClass()] = null;

$filter = new FilterStorage();
$filter->other = $storage;

var_dump($storage->removeAllExcept($filter));
var_dump(count($storage));

?>
--EXPECT--
int(0)
int(0)
