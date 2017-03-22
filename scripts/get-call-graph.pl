#!/usr/bin/env perl

use strict;
use Data::Dumper;

BEGIN { $| = 1 }

my $services_funcs = [
  "openscholar/modules/contrib/services/docs/services.authentication.api.php _services_oauth_authenticate_call",
  "openscholar/modules/contrib/services/docs/services.alter.api.php _services_oauth_security_settings_authorization",
  "openscholar/modules/contrib/services/docs/services.services.api.php _services_oauth_security_settings",
  "openscholar/modules/contrib/services/docs/services.servers.api.php _services_oauth_default_security_settings",
  "openscholar/modules/contrib/services/docs/services.servers.api.php _services_oauth_controller_settings",
  "openscholar/modules/contrib/services/auth/services_oauth/services_oauth.module services_oauth_services_authentication_info",
  "openscholar/modules/contrib/services/docs/services.alter.api.php hook_services_request_preprocess_alter",
  "openscholar/modules/contrib/services/docs/services.alter.api.php hook_services_request_postprocess_alter",
  "openscholar/modules/contrib/services/docs/services.authentication.api.php hook_services_authentication_info",
  "openscholar/modules/contrib/services/docs/services.servers.api.php hook_server_info",
  "openscholar/modules/contrib/services/docs/services.servers.api.php hook_server",
  "openscholar/modules/contrib/services/docs/services.services.api.php hook_services_resources",
  "openscholar/modules/contrib/services/docs/services.versions.api.php _system_resource_set_variable_update_1_1",
  "openscholar/modules/contrib/services/docs/services.versions.api.php _system_resource_set_variable_update_1_2",
  "openscholar/modules/contrib/services/includes/services.resource_build.inc _services_build_resources",
  "openscholar/modules/contrib/services/includes/services.resource_build.inc _services_resource_upgrade",
  "openscholar/modules/contrib/services/includes/services.resource_build.inc _services_apply_endpoint",
  "openscholar/modules/contrib/services/includes/services.resource_build.inc _services_core_resources",
  "openscholar/modules/contrib/services/includes/services.resource_update.inc services_resource_api_update_3002",
  "openscholar/modules/contrib/services/includes/services.runtime.inc services_controller_execute",
  "openscholar/modules/contrib/services/includes/services.runtime.inc _services_controller_execute_preserve_user_switch_anonymous",
  "openscholar/modules/contrib/services/includes/services.runtime.inc _services_controller_execute_restore_user",
  "openscholar/modules/contrib/services/includes/services.runtime.inc _services_controller_execute_internals",
  "openscholar/modules/contrib/services/includes/services.runtime.inc services_authentication_info",
  "openscholar/modules/contrib/services/includes/services.runtime.inc services_auth_invoke",
  "openscholar/modules/contrib/services/includes/services.runtime.inc services_resource_uri",
  "openscholar/modules/contrib/services/includes/services.runtime.inc services_set_server_info",
  "openscholar/modules/contrib/services/includes/services.runtime.inc services_set_server_info_from_array",
  "openscholar/modules/contrib/services/includes/services.runtime.inc services_get_server_info",
  "openscholar/modules/contrib/services/includes/services.runtime.inc services_server_info_object",
  "openscholar/modules/contrib/services/includes/services.runtime.inc services_error",
  "openscholar/modules/contrib/services/includes/services.runtime.inc services_node_load",
  "openscholar/modules/contrib/services/includes/services.runtime.inc _services_arg_value",
  "openscholar/modules/contrib/services/includes/services.runtime.inc _services_access_value",
  "openscholar/modules/contrib/services/includes/services.runtime.inc _services_authenticate_user",
  "openscholar/modules/contrib/services/includes/services.runtime.inc _services_run_access_callback",
  "openscholar/modules/contrib/services/plugins/export_ui/services_ctools_export_ui.class.php services_edit_form_endpoint_authentication",
  "openscholar/modules/contrib/services/plugins/export_ui/services_ctools_export_ui.class.php services_edit_form_endpoint_authentication_submit",
  "openscholar/modules/contrib/services/plugins/export_ui/services_ctools_export_ui.class.php services_edit_form_endpoint_server",
  "openscholar/modules/contrib/services/plugins/export_ui/services_ctools_export_ui.class.php services_edit_form_endpoint_server_submit",
  "openscholar/modules/contrib/services/plugins/export_ui/services_ctools_export_ui.class.php services_edit_endpoint_resources",
  "openscholar/modules/contrib/services/plugins/export_ui/services_ctools_export_ui.class.php services_edit_form_endpoint_resources",
  "openscholar/modules/contrib/services/plugins/export_ui/services_ctools_export_ui.class.php services_edit_form_endpoint_resources_validate",
  "openscholar/modules/contrib/services/plugins/export_ui/services_ctools_export_ui.class.php services_edit_form_endpoint_resources_submit",
  "openscholar/modules/contrib/services/plugins/export_ui/services_ctools_export_ui.class.php services_get_update_versions",
  "openscholar/modules/contrib/services/plugins/export_ui/services_ctools_export_ui.inc services_ctools_export_ui_form",
  "openscholar/modules/contrib/services/plugins/export_ui/services_ctools_export_ui.inc services_ctools_export_ui_form_validate",
  "openscholar/modules/contrib/services/plugins/export_ui/services_ctools_export_ui.inc services_ctools_export_ui_form_machine_name_exists",
  "openscholar/modules/contrib/services/plugins/export_ui/services_ctools_export_ui.inc services_ctools_export_ui_form_submit",
  "openscholar/modules/contrib/services/resources/comment_resource.inc _comment_resource_definition",
  "openscholar/modules/contrib/services/resources/comment_resource.inc _comment_resource_create",
  "openscholar/modules/contrib/services/resources/comment_resource.inc _comment_resource_retrieve",
  "openscholar/modules/contrib/services/resources/comment_resource.inc _comment_resource_update",
  "openscholar/modules/contrib/services/resources/comment_resource.inc _comment_resource_delete",
  "openscholar/modules/contrib/services/resources/comment_resource.inc _comment_resource_index",
  "openscholar/modules/contrib/services/resources/comment_resource.inc _comment_resource_count_all",
  "openscholar/modules/contrib/services/resources/comment_resource.inc _comment_resource_count_new",
  "openscholar/modules/contrib/services/resources/comment_resource.inc _comment_resource_access",
  "openscholar/modules/contrib/services/resources/file_resource.inc _file_resource_definition",
  "openscholar/modules/contrib/services/resources/file_resource.inc _file_resource_create",
  "openscholar/modules/contrib/services/resources/file_resource.inc _file_resource_create_raw",
  "openscholar/modules/contrib/services/resources/file_resource.inc _file_resource_retrieve",
  "openscholar/modules/contrib/services/resources/file_resource.inc _file_resource_delete",
  "openscholar/modules/contrib/services/resources/file_resource.inc _file_resource_index",
  "openscholar/modules/contrib/services/resources/file_resource.inc _file_resource_access",
  "openscholar/modules/contrib/services/resources/file_resource.inc _file_resource_node_access",
  "openscholar/modules/contrib/services/resources/node_resource.inc _node_resource_definition",
  "openscholar/modules/contrib/services/resources/node_resource.inc _node_resource_retrieve",
  "openscholar/modules/contrib/services/resources/node_resource.inc _node_resource_create",
  "openscholar/modules/contrib/services/resources/node_resource.inc _node_resource_validate_type",
  "openscholar/modules/contrib/services/resources/node_resource.inc _node_resource_update",
  "openscholar/modules/contrib/services/resources/node_resource.inc _node_resource_delete",
  "openscholar/modules/contrib/services/resources/node_resource.inc _node_resource_index",
  "openscholar/modules/contrib/services/resources/node_resource.inc _node_resource_access",
  "openscholar/modules/contrib/services/resources/node_resource.inc _node_resource_load_node_files",
  "openscholar/modules/contrib/services/resources/node_resource.inc _node_resource_load_node_comments",
  "openscholar/modules/contrib/services/resources/node_resource.inc _node_resource_attach_file",
  "openscholar/modules/contrib/services/resources/node_resource.inc _node_resource_file_save_upload",
  "openscholar/modules/contrib/services/resources/node_resource.inc _node_resource_validate_node_type_field_name",
  "openscholar/modules/contrib/services/resources/system_resource.inc _system_resource_definition",
  "openscholar/modules/contrib/services/resources/system_resource.inc _system_resource_connect",
  "openscholar/modules/contrib/services/resources/taxonomy_resource.inc _taxonomy_resource_definition",
  "openscholar/modules/contrib/services/resources/taxonomy_resource.inc _taxonomy_term_resource_retrieve",
  "openscholar/modules/contrib/services/resources/taxonomy_resource.inc _taxonomy_term_resource_create",
  "openscholar/modules/contrib/services/resources/taxonomy_resource.inc _taxonomy_term_resource_update",
  "openscholar/modules/contrib/services/resources/taxonomy_resource.inc _taxonomy_term_resource_delete",
  "openscholar/modules/contrib/services/resources/taxonomy_resource.inc _taxonomy_vocabulary_resource_retrieve",
  "openscholar/modules/contrib/services/resources/taxonomy_resource.inc _taxonomy_vocabulary_resource_create",
  "openscholar/modules/contrib/services/resources/taxonomy_resource.inc _taxonomy_vocabulary_resource_update",
  "openscholar/modules/contrib/services/resources/taxonomy_resource.inc _taxonomy_vocabulary_resource_delete",
  "openscholar/modules/contrib/services/resources/taxonomy_resource.inc taxonomy_service_get_tree",
  "openscholar/modules/contrib/services/resources/taxonomy_resource.inc taxonomy_service_select_nodes",
  "openscholar/modules/contrib/services/resources/taxonomy_resource.inc _taxonomy_resource_update_access",
  "openscholar/modules/contrib/services/resources/taxonomy_resource.inc _taxonomy_resource_create_access",
  "openscholar/modules/contrib/services/resources/taxonomy_resource.inc _taxonomy_resource_delete_access",
  "openscholar/modules/contrib/services/resources/taxonomy_resource.inc _taxonomy_term_resource_index",
  "openscholar/modules/contrib/services/resources/taxonomy_resource.inc _taxonomy_vocabulary_resource_index",
  "openscholar/modules/contrib/services/resources/user_resource.inc _user_resource_definition",
  "openscholar/modules/contrib/services/resources/user_resource.inc _user_resource_retrieve",
  "openscholar/modules/contrib/services/resources/user_resource.inc _user_resource_create",
  "openscholar/modules/contrib/services/resources/user_resource.inc _user_resource_update",
  "openscholar/modules/contrib/services/resources/user_resource.inc _user_resource_delete",
  "openscholar/modules/contrib/services/resources/user_resource.inc _user_resource_login",
  "openscholar/modules/contrib/services/resources/user_resource.inc _user_resource_logout",
  "openscholar/modules/contrib/services/resources/user_resource.inc _user_resource_logout_update_1_1",
  "openscholar/modules/contrib/services/resources/user_resource.inc _user_resource_logout_1_1",
  "openscholar/modules/contrib/services/resources/user_resource.inc _user_resource_index",
  "openscholar/modules/contrib/services/resources/user_resource.inc _user_resource_access",
  "openscholar/modules/contrib/services/servers/rest_server/formats/xcal_format.module xcal_format_autoload_info",
  "openscholar/modules/contrib/services/servers/rest_server/formats/xcal_format.module xcal_format_rest_server_response_formatters_alter",
  "openscholar/modules/contrib/services/servers/rest_server/lib/bencode.php bencode",

  "openscholar/modules/contrib/services/servers/rest_server/rest_server.api.php hook_rest_server_request_parsers_alter",
  "openscholar/modules/contrib/services/servers/rest_server/rest_server.api.php hook_rest_server_response_formatters_alter",
  "openscholar/modules/contrib/services/servers/rest_server/rest_server.api.php hook_rest_server_execute_errors_alter",
  "openscholar/modules/contrib/services/servers/rest_server/rest_server.inc _rest_server_settings",
  "openscholar/modules/contrib/services/servers/rest_server/rest_server.inc _rest_server_settings_checkboxes_attributes",
  "openscholar/modules/contrib/services/servers/rest_server/rest_server.inc _rest_server_settings_submit",
  "openscholar/modules/contrib/services/servers/rest_server/rest_server.inc _rest_server_settings_not_zero",
  "openscholar/modules/contrib/services/servers/rest_server/rest_server.module rest_server_server_info",
  "openscholar/modules/contrib/services/servers/rest_server/rest_server.module rest_server_server",
  "openscholar/modules/contrib/services/servers/rest_server/rest_server.module rest_server_request_parsers",
  "openscholar/modules/contrib/services/servers/rest_server/rest_server.module rest_server_response_formatters",
  "openscholar/modules/contrib/services/servers/rest_server/rest_server.module rest_server_setup_settings",
  "openscholar/modules/contrib/services/servers/rest_server/rest_server.module _rest_server_add_default_and_remove_unknown",
  "openscholar/modules/contrib/services/servers/rest_server/rest_server.module rest_server_services_resources_alter",
  "openscholar/modules/contrib/services/servers/rest_server/rest_server.module rest_server_libraries_info",
  "openscholar/modules/contrib/services/servers/xmlrpc_server/xmlrpc_server.module xmlrpc_server_server_info",
  "openscholar/modules/contrib/services/servers/xmlrpc_server/xmlrpc_server.module xmlrpc_server_server",
  "openscholar/modules/contrib/services/servers/xmlrpc_server/xmlrpc_server.module xmlrpc_server_xmlrpc",
  "openscholar/modules/contrib/services/servers/xmlrpc_server/xmlrpc_server.module xmlrpc_server_call_wrapper",
  "openscholar/modules/contrib/services/services.admin.inc theme_services_resource_table",
  "openscholar/modules/contrib/services/services.module services_help",
  "openscholar/modules/contrib/services/services.module services_permission",
  "openscholar/modules/contrib/services/services.module services_hook_info",
  "openscholar/modules/contrib/services/services.module services_menu",
  "openscholar/modules/contrib/services/services.module services_ctools_plugin_api",
  "openscholar/modules/contrib/services/services.module services_ctools_plugin_directory",
  "openscholar/modules/contrib/services/services.module services_access_menu",
  "openscholar/modules/contrib/services/services.module services_theme",
  "openscholar/modules/contrib/services/services.module services_get_servers",
  "openscholar/modules/contrib/services/services.module services_endpoint_callback",
  "openscholar/modules/contrib/services/services.module services_endpoint_new",
  "openscholar/modules/contrib/services/services.module services_endpoint_load",
  "openscholar/modules/contrib/services/services.module services_endpoint_load_all",
  "openscholar/modules/contrib/services/services.module services_endpoint_save",
  "openscholar/modules/contrib/services/services.module services_endpoint_delete",
  "openscholar/modules/contrib/services/services.module services_endpoint_export",
  "openscholar/modules/contrib/services/services.module services_get_resources",
  "openscholar/modules/contrib/services/services.module services_resource_api_version_info",
  "openscholar/modules/contrib/services/services.module services_services_resources",
  "openscholar/modules/contrib/services/services.module services_services_authentication_info",
  "openscholar/modules/contrib/services/services.module _services_sessions_authenticate_call",
  "openscholar/modules/contrib/services/services.module services_operation_class_info",
  "openscholar/modules/contrib/services/services.module services_controllers_list",
  "openscholar/modules/contrib/services/services.module services_controller_get",
  "openscholar/modules/contrib/services/services.module services_get_updates",
  "openscholar/modules/contrib/services/services.module _services_version_header_options",
  "openscholar/modules/contrib/services/services.module services_get_resource_api_version",
  "openscholar/modules/contrib/services/services.module services_request_apply_version",
  "openscholar/modules/contrib/services/services.module services_resources_as_procedures",
  "openscholar/modules/contrib/services/services.module services_resource_build_index_query",
  "openscholar/modules/contrib/services/services.module services_str_getcsv",
  "openscholar/modules/contrib/services/services.module services_resource_build_index_list",
  "openscholar/modules/contrib/services/services.module services_remove_user_data",
  "openscholar/modules/contrib/services/services.module services_resource_execute_index_query",
  "openscholar/modules/contrib/services/tests/services_test_resource/services_test_resource.module services_test_resource_services_resources",
  "openscholar/modules/contrib/services/tests/services_test_resource/services_test_resource.module _services_test_resource_retrieve",
  "openscholar/modules/contrib/services/tests/services_test_resource/services_test_resource.module _services_test_resource_retrieve_update_1_1",
  "openscholar/modules/contrib/services/tests/services_test_resource/services_test_resource.module _services_test_resource_retrieve_update_1_2",
  "openscholar/modules/contrib/services/tests/services_test_resource/services_test_resource.module _services_test_resource_retrieve_callback_1_2",
  "openscholar/modules/contrib/services/tests/services_test_resource/services_test_resource.module _services_test_resource_action_retrieve",
  "openscholar/modules/contrib/services/tests/services_test_resource/services_test_resource.module _services_test_resource_targeted_action_test",
  "openscholar/modules/contrib/services/tests/services_test_resource/services_test_resource.module _services_test_resource_access",
  "openscholar/modules/contrib/services/tests/services_test_resource/services_test_resource.module _services_arguments_test_resource_retrieve",
];

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

