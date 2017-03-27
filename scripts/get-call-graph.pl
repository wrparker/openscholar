#!/usr/bin/env perl

use strict;
use Data::Dumper;

BEGIN { $| = 1 }

# Add starting function(s) to trace [upwards] from
my $services_funcs = [];

my $symbol_hash;

&get_call_graph($services_funcs, 0);

# Recurse until we're are out of subroutines
sub get_call_graph {
  my ($funcs, $depth) = @_;

  # print "\nfuncs = " . Dumper($funcs);

  if (!$funcs or ($depth > 10)) {
    # print "\n--\n";
    return;
  }

  foreach my $f (@{$funcs}) {
    print ("  " x $depth);
    print "$f\n";
    # flush();
    my ($file, $func) = split(/\s+/, $f);

    # skip past behat testing directories
    if ($file =~ /\/behat\//) {
      next;
    }

    my $a = &get_cscope_L3($func);
    &get_call_graph($a, $depth + 1);
  }
}

sub get_cscope_L3 {
  my ($func) = @_;

  my $ret;

  my $output = `cscope -L3${func}`;
  my @lines = split(/(\r|\n)/, $output);

  foreach my $l (@lines) {

    if ($l =~ /(\S+\s+\w+)/) {

      # print "\n+ = " . Dumper($+);

      # Skip "__construct" and "array"
      if ($+ !~ /\b(__construct|array)\b/) {
        push(@$ret, $+);
      }
    }
  }

  # print "\nret = " . Dumper($ret);

  return $ret;
}

