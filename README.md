# REST API jobs management

This api is design to manage jobs in a queue.

## Install

    mount it in a php server of your choice.
    in the root folder there is a file "script.sql" with the BBDD and example data.
    Change the credencials on config/db.php.

## Requests

### Get a job by id
`GET api/jobs/getJobId.php?id={id}`

### Response

    {
        "id": 2,
        "submitter": 2,
        "processor": 22,
        "command": "ipconfig",
        "status": "processing"
    }
    
### Error
    {
         "message": "Job not found"
    }
    
### Get first priority job
`GET api/jobs/priority.php?processor={id}`<br>
the jobs call by this method change it's status to "processing" and assign the processor.

### Response

    {
        "id": "5",
        "submitter": "11",
        "command": "testcommand"
    }
    
### Get average processing time
`GET api/jobs/processingTime.php`

### Response

    {
        "message": "the tasks are being processed in an average time of 533.0400 seconds"
    }
    
### POST add new job or update job
`POST api/api/jobs/create.php`

### Add a new job body
    Content-Type: application/json
    {
        "submitter":2,
        "processor":1,
        "command":"ipconfig"
    }
    
    processor is optional wich in that case:
    Content-Type: application/json
    {
            "submitter":2,
            "processor":"",
            "command":"ipconfig"
    }
    
### Response
    {
        "message": "Job created",
        "id": 82
    }
### Error
    {
         "message": "submitter must be int"
    }

### Update a new job body
    Content-Type: application/json
    {
        "id":2,
        "submitter":2,
        "processor":1,
        "command":"ipconfig"
    }
    
    processor is optional wich in that case:
    Content-Type: application/json
    {
            "id":2,
            "submitter":2,
            "processor":"",
            "command":"ipconfig"
    }
    
### Response
    {
         "message": "Job comppleted cant be updated"
    }
### Error
    {
         "message": "Job comppleted cant be updated"
    }
    
### PUT update job status
`PUT api/api/jobs/update.php`

### Body
    Content-Type: application/json
    {
    	"id":6,
    	"status":1
    }
    
### Response
    {
        "message": "Job updated"
    }
### Error
    {
         "message": "id must be int"
    }
