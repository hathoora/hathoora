<?php
namespace site\controller;

use hathoora\controller\CRUD;

/**
 * aoi controller
 */
class apiController extends CRUD
{

    /*
    GET
    200 OK
    400 Bad Request (when input criteria not correct)

    POST
    202 Accepted (returned by authorization method)


    401 Unauthorized (also returned by authorization)


    201 Created (when creating a new resource; I also set the location header)
    400 Bad Request (when data for creating new entity is invalid or transaction rollback)

    PUT

    Same as POST
    201 Ok
    400 Bad Request

    DELETE
    200 OK
    404 Not Found (same as GET)*/

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * URL: GET /room to return list of all rooms
     */
    public function collection()
    {
        die('COLLECTIOn -> <br/>');
    }

    /**
     * GET /room/12 - Retrieves a specific room
     */
    public function read($id = null)
    {
        die("GET -> $id");
    }


    /**
     * POST /room - Creates a new room
     */
    public function create()
    {
        die("POST -");
    }

    /**
     * PUT /room/12 - Updates room #12
     */
    public function update($id)
    {
        die("PUT -> $id");
    }

    /**
     * DELETE /room/12 - Deletes ticket #12
     */
    public function delete($id)
    {
        die("DEL -> $id");
    }
}
