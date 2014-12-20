# Configuration

Concerto uses a `Solo` ('Strategy') to determine the `Soloist` ('Tenant') from Symfony's `Request` object. You'll need to configure the name of the `Soloist` class, the name of the `Solo` as written in `config.yml`, and its constructor arguments.

```yml
#config.yml

concerto:
  
  # The class we're aiming to find
  soloist_class: Your\Bundle\YourBundle\Entity\YourTenantClass
  
  # The name of the solo we'll use to find it  
  solo_name: name_of_solo_from_below

  
  solos:
    # configs for different Solos. Concerto comes with a HostnameSolo and a RepositorySolo, but
    # you can add whatever custom ones you like. These values build a service definition for
    # @concerto.solo.
    
    hostname:
    # HostnameSolo takes one argument: The name of the field on your Soloist entities which holds
    # (you guessed it) the hostname.
      arguments:
        - fieldName
        
    repository:
    # RepositorySolo takes two arguments: A repository-as-a-service, and the name of the method
    # on that repository to use to do the lookup. That method must take only one argument: the 
    # Request.
      arguments:
        - @repoName
        - repoMethodName
        
    your_custom_solo_1:
      class: Your\Bundle\YourBundle\Solo\YourSoloName
      arguments:
        - ARG1
        - ARG2
        # ...
        - ARGN
      
    your_custom_solo_2: 
      service: @custom_solo_service_you_defined_elsewhere
```

A properly configured `Solo` has the form:

```yml
<name>:
  class: <class name>
  arguments:
    - <constructor argument 1>
    - <constructor argument 2>
    # ...
    - <constructor argument n>
```
OR
```yml
<name>:
  service: @<service id>
```

note: Concerto defaults to the HostnameSolo. If that's the `Solo` you plan on using, you can leave out the `solo_name` part but still need to configure `hostname.arguments`.

With that set up, you can [get started](getting_started.md).
