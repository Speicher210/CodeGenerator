PHP file generator
==================

It is possible to generate PHP files by specifying the elements of the file.

This elements are:

- file documentation
- file namespace
- required files
- uses statements
- objects (classes, interfaces, etc)
- extra body (any code that also needs to be in the file)


If the objects added to the file are not in the same namespace as the file then each object will be generated in it's own namespace.
If everything is in the same namespace, one namespace will be declared and everything will be generated in that namespace.
Same namespace is assumed when all the namespaces are the same or `NULL`.

[Different namespaces](generate-file-differentnamespaces.md)

[Same namespaces](generate-file-samenamespaces.md)