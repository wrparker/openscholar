Feature: Testing taxonomy CRUD.

  @api @restful
  Scenario: Testing CRUD actions for Taxonomy.
    Given I "create" a term as "john" with the settings:
      | label | vocab             |
      | Water | science_personal1 |
    Given I "update" a term as "john" with the settings:
      | label       | id    |
      | Water - new | PREV  |
    Given I "delete" a term as "john" with the settings:
      | id    |
      | PREV  |

