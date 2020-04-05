# Object Storage Cleaner

## How to Install

Make sure index.php and deleteObject.sh are exacutable

```bash
chmod 744 index.php
```

### Use

After that you rename ENV.php.dist to ENV.php and fill in the credentials.
Then execute index.php.

```bash
php index.php
```

### Test mode

It will automatically be in test mode. Like that you will not be accidentally deleting your entire bucket.
In test mode it only write the results to a log file.
Change `$testing` to `false` for the real deal.
Unfortunately it will not delete anything and it gives an error... :( (please help)

But I wrote a solution for that: [deleteObject.sh].
This needs the [aws cli](https://aws.amazon.com/cli/) and you must be logged in.
You still need to run index.php because you need the log file it creates. You must rename the desired log to `objects.log`.
It is importand that each path+filename is separated with a new line.
Within [deleteObject.sh] change {Bucket Name} and {Endpoint URL}.

[deleteObject.sh]:deleteObject.sh

#### Notes

On line 48: `...$this->filesystem->listContents(STARTDIR, false)` this must always be false.

I am not responsible for anything if something went south with this script. Use at own risk.
