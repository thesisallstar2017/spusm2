<?php namespace App\Models;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    const ADMIN = 'admin';
    const FACULTY  = 'faculty';
    const STUDENT = 'student';

    protected $fillable = ['id', 'name', 'display_name', 'description'];
}