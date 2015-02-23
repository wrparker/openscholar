Feature:
  Creating nodes via rest.

  @restful
  Scenario: Creating biblio via rest.
    Given I create a new node of "biblio" as "john" with the settings:
      | Label       | Body                  | vsite | type  |
      | Rest biblio | This is a test Biblio | john  | 101   |

  @restful
  Scenario: Creating bio via rest.
    Given I delete the node "John doe biography"
     Then I create a new node of "bio" as "john" with the settings:
      | Label       | Body                | vsite |
      | Rest bio    | This is a test bio  | john  |

  @restful
  Scenario: Creating blog via rest.
    Given I create a new node of "blog" as "john" with the settings:
      | Label     | Body                 | vsite |
      | Rest blog | This is a test blog  | john  |

  @restful
  Scenario: Creating book via rest.
    Given I create a new node of "book" as "john" with the settings:
      | Label     | Body                | vsite |
      | Rest book | This is a test book | john  |

  @restful
  Scenario: Creating class via rest.
    Given I create a new node of "class" as "john" with the settings:
      | Label       | Body                  | vsite |
      | Rest class  | This is a test class  | john  |

  @restful
  Scenario: Creating book via rest.
    Given I create a new node of "class_material" as "john" with the settings:
      | Label               | Body                           | vsite | Parent     |
      | Rest class material | This is a test class material  | john  | Rest class |

  @restful
  Scenario: Creating book via rest.
    Given I delete the node "John doe's curriculum"
     Then I create a new node of "cv" as "john" with the settings:
      | Label   | Body               | vsite  |
      | Rest CV | This is a test CV  | john   |

  @restful
  Scenario: Creating book via rest.
     Given I create a new node of "event" as "john" with the settings:
      | Label       | Body                  | vsite  | Start date |
      | Rest event  | This is a test event  | john   | 1/1/2010   |

  @restful
  Scenario: Creating faq via rest.
     Given I create a new node of "faq" as "john" with the settings:
      | Label     | Body                | vsite  |
      | Rest FAQ  | This is a test FAQ  | john   |

  @restful
  Scenario: Creating faq via rest.
     Given I create a new node of "feed" as "john" with the settings:
      | Label     | Body                | vsite  | Field url      |
      | Rest feed | This is a test feed | john   | http://foo.bar |

  @restful
  Scenario: Creating faq via rest.
     Given I create a new node of "image_gallery" as "john" with the settings:
      | Label         | vsite  | columns  | rows  | files               |
      | Rest gallery  | john   | 3        | 4     | jfk_1.jpg,jfk_2.jpg |

  @restful
  Scenario: Creating faq via rest.
     Given I create a new node of "news" as "john" with the settings:
      | Label     | date      | body                | vsite  |
      | Rest news | 1/1/2012  | This is a rest nest | john   |

  @restful
  Scenario: Creating faq via rest.
     Given I create a new node of "page" as "john" with the settings:
      | Label     | body                | vsite  | path       |
      | Rest page | This is a rest page | john   | rest-path  |
