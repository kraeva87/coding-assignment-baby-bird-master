<?php

namespace Svetlana\StudyProject;

use SQLite3;

class Database extends SQLite3
{
    function __construct()
    {
        parent::__construct(__DIR__ . '/../db/sqlite.db', SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
    }
}
