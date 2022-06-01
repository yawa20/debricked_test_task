I know about recommendation to not separate project for bundles.  
But I prefer to separate application to bundles cuz it makes easy to slit project to independed modules
with own configuration and code base.

Only 'App\\' namespace can have dependencies from other bundles.

Listed bundles SHOULD NOT depend on any other. 