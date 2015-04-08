Feature: Testing taxonomy CRUD.

  @api @restful
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

  @api @restful
  Scenario: Testing CRUD actions for taxonomy.
    Given I "create" a vocabulary as "john" with the settings:
      | label   | vsite | machine name  |
      | Testing | john  | testing_vocab |
    Given I "patch" a vocabulary as "john" with the settings:
      | label         |
      | Testing - new |
    Given I "delete" a vocabulary as "john" with the settings:
      | id    |
      | PREV  |
