<?php

namespace Inviqa\Integration;

class Jenkins implements ExternalIntegrationHandler
{
    protected $dbus;

    public function __construct(\DbusObject $dbus)
    {
        $this->dbus = $dbus;
    }

    public function handle($input)
    {
        if (!($payload = json_decode($input))) {
            return;
        }

        if (isset($_REQUEST['filter'])) {
            $match = $this->matchFilters(
                (array) $_REQUEST['filter'],
                $_REQUEST,
                $payload
            );

            if ($match !== true) {
                throw new \Exception('No match found for supplied filter.');
            }
        }

        $info = "";

        switch($payload->build->phase) {
            case 'STARTED':
                $info = sprintf(
                    "%s build %s has started at %s.",
                    $payload->name,
                    $payload->build->number,
                    $payload->build->full_url
                );
                break;
            case 'FINISHED':
                $info = sprintf(
                    "%s build number %s finished with status %s at %s.",
                    $payload->name,
                    $payload->build->number,
                    $payload->build->status,
                    $payload->build->full_url
                );
                break;
        }

        if (!empty($info)) {
            $this->dbus->Invoke("CHATMESSAGE {$_REQUEST['id']} $info");
        }
    }

    /**
     * Determines if requested filters mach payload.
     *
     * Nested fields can be accessed by using a hyphen.
     * e.g. for payload { build: {status: success } }
     * build status can be filtered with "build-status"
     *
     * @param mixed $filters String (single field) or array of fields to filter on.
     * @param array $input Input that specifies filter values.
     * @param object $payload Parsed payload.
     *
     * @return mixed Boolean true if filters are OK, string with failure reason otherwise.
     */
    private function matchFilters($filters, $input, $payload)
    {
        foreach ($filters as $filter) {
            $key = "filter-{$filter}";
            if (isset($input[$key])) {
                $value = $this->getPayloadValue($filter, $payload);
                if ($input[$key] != $value) {
                    return "'$filter' filter did not match ('{$input[$key]}' != '$value')\n";
                }
            }
        }
        return true;
    }

    /**
     * Traverses the payload and returns the value specified by path.
     *
     * Nesting is represented by a hyphen.
     *
     * @param string $path Path to field
     * @param object $payload Parsed payload
     * @return mixed Null if path could not be accessed, value of field specified by path otherwise
     */
    private function getPayloadValue($path, $payload)
    {
        $current = $payload;
        $path = explode('-', $path);
        foreach ($path as $elem) {
            if (!is_object($current) || !isset($current->{$elem})) {
                return null;
            }
            $current =& $current->{$elem};
        }
        return $current;
    }
}
