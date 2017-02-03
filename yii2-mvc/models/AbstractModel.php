<?php

namespace app\models;

use yii\db\ActiveRecord;

/*
 * BranchLabs Applicant Project
 */

/**
 * Description of AbstractModel
 *
 * @author Awoyo Oluwatoyin
 */
abstract class AbstractModel extends ActiveRecord
{
    /**
     * Main table name
     *
     * @var string
     */
    protected static $_table;
    /**
     * Main table primary key
     *
     * @var string
     */
    protected static $_pk;

    /**
     * Returns the primary key name(s) for this AR class.
     * The default implementation will return the primary key(s) as declared
     * in the DB table that is associated with this AR class.
     *
     * If the DB table does not declare any primary key, you should override
     * this method to return the attributes that you want to use as primary keys
     * for this AR class.
     *
     * Note that an array should be returned even for a table with single primary key.
     *
     * @return string[] the primary keys of the associated database table.
     */
    public static function primaryKey()
    {
        return [static::$_pk];
    }
}
