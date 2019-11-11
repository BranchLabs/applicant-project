### First Words
Of course this ORM is far from ideal. However it is a very simple representation of an actual ORM.

### How to Install
You need to have `docker` installed.

* Copy `.env.dist` and paste as `.env`
* **Optional**; change variables in `.env`. I committed mysql credentials for container to make it easier for you.
* Add hosts record such as `172.111.0.1 orm.branchlabs.local` to your `/etc/hosts`. 
If you changed `DOCKER_SUBNET` / `DOCKER_GATEWAY_IP` reflect the IP accordingly.

* `$ make build up` to build and start the containers
* `$ make stop` to stop containers
* `$ make database_create` to create database within container

### Changes
Since I was given the choice of a framework, knowing you are working with Magento a lot, I didn't really wanted to go with requested way.
I was asked for trade offs while selecting technologies or choices during my initial interview.

I had to make some changes in that regard and hoping you will be happy with my choices.

##### Separate Responsibilities

I don't want to integrate a responsibility of another class to Entity. Hence `$contact->load(1)` or `$contact->save()` 
etc. is not possible.

Which is why `Repository` classes are responsible for this specific feature; loading data. Hence some of the available methods;
* `$repository->save($contact)`
* `$repository->delete($id)`
* `$repository->insert($contact)` 
* `$repository->update($contact)`
* `$contacts = $repository->findAll()`
* `$contact = $repository->find($id)`
* `$contacts = $repository->findBy(['name' => 'Donald'])`

##### Using Magic Methods
 
Usually it is not a good idea to use magic methods (there are few exceptions to this). Especially on entity level, 
it makes it really hard to debug and read the code. Increases possibility to introduce a bug to the application easier.
 
Considering I was given free choice of PHP version, it became more crucial to use type hinted function arguments 
and return values. Hence making it easier to debug & read.

There are similar methods;
* Instead of `$contact->getData()`, there is `$contact->toArray()`
* Instead of `$contact->setData(['name' => 'Donald Knuth'])`, there is `$contact = (new Contact)->hydrate(['name' => 'Donald'])`

However similar functionality (not exactly the same as I just wanted to demonstrate it) can be added through `src/Service/Orm/Entity/BurnItWithFireTrait.php` trait such as;

```php
use App\Service\Orm\Entity\BurnItWithFireTrait;

class Contact {
    use BurnItWithFireTrait;
    
    //....
}
```

### Important

I was initially going to spend more time till Friday (5 more days), about 1 - 2 hours a day to prepare more ORM features instead of a simple one for fun. 
I have had a quick project / consulting gig so I had to cut it short. However I believe this is already more than you asked for.

* `docker-compose.yml` has `triplebits/*` as images. These are my own repos. 
Reason is for example PHP (CLI or FPM) has xDebug in it, composer has tiny in it, nginx has few additional settings 
such ready, self-signed default SSL and few additional setup such as spitting out logs to docker logs hence making it 
easier for me to follow up access & error log via my IDE or CLI.

* I prefer to deal with objects over statics (there are few exceptions or similar usage case; class constants) when possible. 
While one might think it is easier to code, it could easily lead to anti-pattern / anti-pattern alike choices and 
potentially introducing bugs due to having variables -if any- shared among instances.

* Objects are easier to read & understand as you exactly know that you are working on the instance itself. I always prefer 
readability over literally anything when it comes to code.

* It has the same principle as MVC but it is not actually MVC. It is rather ADR (Action Domain Responder). 
Reason is I find this way a lot clear, directly to the point over common MVC pattern. This being said, 
it is nearly the same as MVC in my sample, just better for readability and responsibilities.

* Why readability > anything? Introducing a new team member or going back to the project after a while, having constant 
code base & high readability would lead to less integration / down the memory line / code reading to understand what is 
happening. Hence would lead to better performing team / work pace overall.

* ORM is basically under `src/Service/Orm`. Additionally there are `src/Entity` and `src/Repository` directories. 
I do not like them combined as this is a bad pattern. Repository can return an Entity or a Collection of an Entity / Entity Collection.

* `MysqlQueryBuilder` is separated, one might think why to keep such simple thing outside of `MysqlAdapter`. 
Reason is quite simple; keeping the code cleaner. Hence making it easier to prepare unified query language for the ORM, 
adding expressions etc. this would be the file we would need to edit / modify.

* One might think there are redundant files such as `QueryDTO`, `MysqlCredentials` classes. These are here to solve 
specific problems such as making it easier to deal with a lot of parameters, constructor telescoping etc. 
Hence making it “cleaner” code.

* ORM prepared in a way to be partially ready for additional Database support. Such as MongoDB / DynamoDB / Firebase etc. 
using adapters. Partially ready because there is no strategy pattern or similar to decide which adapter to use on runtime. 

* One could say why to deal with that many classes instead of arrays (example MysqlCredentials class). 
That’s due to being easier to work with. For example, when a new developer joins or a dev visits the project after a long time, 
you don’t need to think about “what was the index name for database name? Was it databaseName or name?” while a class / object 
in combination with IDE would make it seamless. Not to mention using DI, such objects can be shared through out the app 
instead of creating let’s say array to pass around which increases the risk of introducing bug and technical debt due to 
wrong index names to say the least.

* Technically `AbstractEntity` is here to keep things “simple”. Under normal circumstances, I’d implement a serializer on 
ORM / Adapter level to serialize / deserialize data on Database Adapter level. However this was the dirty shortcut way 
to get it working with defined properties, less magic and typed setters & getters to demonstrate a little more advanced 
than requested ORM functionality.

### Last Words
I don’t want to get too deep into explanation of each code base here. However we can always discuss why things are the way they are.
For example;
 
**Q:** Why action names are rather long? Why`EditContactAction` rather than `EditAction`, 
it is already inside `Controller/Contact` directory? 

**A:** Simple, I can directly search for the class `EditContactAction` using my IDE rather than searching for `EditAction` 
then look for which one is for `Contact` as it could belong to `Address`, `User` etc.

