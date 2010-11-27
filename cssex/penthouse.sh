ls csszen/*.css | while read testfile; do
    jsc test-osterone.js -- "`cat $testfile`" $testfile
done