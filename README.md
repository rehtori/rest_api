**RESTful API**
----
  API for persons.

* **URL**

  <_The URL Structure (path only, no root url)_>

* **Methods:**
  
  `GET` | `POST` | `DELETE` | `PUT`
  
*  **Optional URL Params**

   Persons listing can also be filtered by name. For example `GET` /api/persons?name=mike 

   `name=[string]`

* **Data Params**

  `POST` /api/persons/
  {"name": "mike", "email": "mike@test.test", "birthday": "1.3.2001", "external_id": "5000"}
  
* **Success Response:**
  
  * **Code:** 2xx <br />
 
* **Error Response:**

  * **Code:** 4xx xxx <br />
  **Content:** `{ error : "error message" }`

  OR

  * **Code:** 500 INTERNAL ERROR <br />



* **Sample Call:**
 * Returns list of persons
 * `GET`	/api/persons  
 
 * Returns list of persons matching the name parameter
 * `GET`	/api/persons?name=rehtori
 
 * Returns person by id parameter
 * `GET`	/api/persons/:id
 
 * Creates new person
 * `POST`	/api/persons
 
 * Deletes person by id parameter
 * `DELETE`	/api/persons/:id
 
 * Updates person by id parameter
 * `PUT`	/api/persons/:id
