Feature: Testing taxonomy CRUD.

  @api
  Scenario: Testing CRUD actions for Taxonomy.
    Given I "create" a term as "john" with the settings:
      | label | vocab             |
      | Water | science_personal1 |
    Given I "patch" a term as "john" with the settings:
      | label       |
      | Water - new |
    Given I "delete" a term as "john" with the settings:
      | id    |
      | PREV  |

