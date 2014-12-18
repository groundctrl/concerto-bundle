# Under the Hood

A new `Request` is made of your app. What happens, in order:

1. [FindSoloistListener](../../EventListener/FindSoloistListener.php)

  Acts on `kernel.request` and does what it sounds like it does. Uses the solo you configured (`YourSolo::getSoloist(Request $request)`). Fires off a `SoloEvents::SOLOIST_FOUND` or `SoloEvents::SOLOIST_NOT_FOUND` to indicate how things went.
 
- [ConductSoloistListener](../../EventListener/ConductSoloistListener.php)

  Acts on `SoloEvents::SOLOIST_FOUND`. Sets the `$soloist` field on the entity manager. Enables the `SoloistFilter` ([here](link.php)) and sets its parameter so your `$em->find`s will only consider those entities that belong to the current `Soloist`.
  
- [ClaimEntitySubscriber](../../EventListener/ClaimEntitySubscriber.php)

  Acts on the `EntityManager`'s (which is in fact Concerto's `Conductor` now) `preFlush` event. Any entities implementing `SoloistAwareInterface` will have `->setSoloist` called on them prior to persistence. This allows you to create and update `SoloistAwareInterface` entities, and call `$em->persist` on them without manually tying them to the proper tenant yourself.