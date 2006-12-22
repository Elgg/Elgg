#!/usr/bin/perl -w
#
# Invocation
#
# unit2lib.pl units/admin/* > admin/lib.php
#

use strict;

print "<?php \n\n";

foreach my $file (@ARGV) {
    my $fname = $file;

    next if $fname =~ m/~$/;

    $fname =~ s/\.php$//; # remove trailing .php
    $fname =~ s/^.+\///;  # remove everything till the last /
    $fname =~ s/^function_//; 

    print "function $fname () {\n\n";
    print <<'EOF';

    // Elgg default globals
    global $function;
    global $log;
    global $actionlog;
    global $errorlog;
    global $messages;
    global $data;
EOF

    open F, "<$file" 
	or die $!;
    while (<F>) {
	unless (m/^(<\?php|\?>)/) {
	    print $_;
	}
    }
    close F;
    print "    return \$run_result;\n";
    print "}\n\n";
}

print "\n\n?>";



