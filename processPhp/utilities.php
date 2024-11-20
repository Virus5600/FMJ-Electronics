<?php
	$ddStyleIncluded = false;
	$fromDd = false;

	// DATABASE FUNCTIONS //
	/**
	 * Fetches all the rows from the database and returns them as an array of objects.
	 *
	 * If the `$useIdAsIndex` is set to true, the id of the row will be used as the index of the array
	 * while also retaining it in the object.
	 *
	 * @param mysqli_result $connection The result of the query.
	 * @param bool $useIdAsIndex Whether to use the id as the index of the array.
	 *
	 * @return array An array of objects containing the rows from the database.
	 */
	function fetchAll(mysqli_result $connection, bool $useIdAsIndex = false): array
	{
		$items = $connection->fetch_all(MYSQLI_ASSOC);
		$items = array_map(fn($i) => (object) $i, $items);

		if ($useIdAsIndex) {
			$keys = array_map(fn($i) => $i->id, $items);
			$values = array_values($items);

			$items = array_combine($keys, $values);
		}

		return $items;
	}

	// DEBUGGING FUNCTIONS //
	/**
	 * Returns the last caller of the function that called this function.
	 * It will return the file name, line number, and the function that called it.
	 *
	 * The `$from` parameter is used to go back a certain number of functions before
	 * returning the caller. By default, it is set to 0, which means it will return
	 * the immediate caller of the function. This parameter is usually changed when
	 * the function is called from another function to serve as a trace back.
	 *
	 * @param int $from The number of functions to go back before returning the caller.
	 *
	 * @return array An array containing the file name, line number, and the function that called it.
	 */
	function traceBack(int $from = 0): array
	{
		$bt = debug_backtrace();
		$lastCaller = array_shift($bt);

		for ($i = 0; $i < $from; $i++)
			$lastCaller = array_shift($bt);

		$lastCaller['file'] = substr(basename($lastCaller['file']), 0 , -4);
		$lastCaller['calledIn'] = array_shift($bt)['function'] ?? "N/A";

		return $lastCaller;
	}

	/**
	 * Similar to `dump` but afterwards, it will die. This function is used for
	 * debugging purposes.
	 *
	 * The `contents` that will be dumped can be of any type. If multiple contents are
	 * passed, they will be dumped one after the other with each being formatted differently.
	 *
	 * Currently custom formatted types are:
	 * - `array`
	 * - `string`
	 * - `boolean`
	 * - `integer`
	 * - `double`
	 * - `NULL`
	 *
	 * If the type is not one of the above, it will be dumped using `var_export` and
	 * will be highlighted as a PHP code block to make it easier to read.
	 *
	 * This function will call `includeStyle` to include the necessary CSS for formatting.
	 *
	 * @param mixed $content The content to dump.
	 *
	 * @return never
	 */
	function dd(...$content): void
	{
		global $fromDd;
		$fromDd = true;
		dump(...$content);
		die();
	}

	/**
	 * Dumps the content. This function is used for debugging purposes.
	 *
	 * The `contents` that will be dumped can be of any type. If multiple contents are
	 * passed, they will be dumped one after the other with each being formatted differently.
	 *
	 * Currently custom formatted types are:
	 * - `array`
	 * - `string`
	 * - `boolean`
	 * - `integer`
	 * - `double`
	 * - `NULL`
	 *
	 * If the type is not one of the above, it will be dumped using `var_export` and
	 * will be highlighted as a PHP code block to make it easier to read.
	 *
	 * This function will call `includeStyle` to include the necessary CSS for formatting.
	 */
	function dump(...$content): void
	{
		global $fromDd;
		$fromCount = 1;
		if ($fromDd) {
			$fromCount = 2;
			$fromDd = false;
		}
		includeStyle();
		$lastCaller = traceBack($fromCount);
		$lastLocationCalled = "{$lastCaller['file']}" . ($lastCaller['calledIn'] = "N/A" ? ".php" : "@{$lastCaller['calledIn']}");

		if (count($content) > 0) {
			echo "<div class='dd'>";
			echo 	"<pre>";
			echo	 	"<span class='comment'>// {$lastLocationCalled}: {$lastCaller['line']}</span>";
			foreach ($content as $c) {
				$type = gettype($c);
				$inf = $type == "array" ? info("array") : info($type);

				handleCommonFormat($c, $inf);
			}
			echo 	"</pre>";
			echo "</div>";
		} else {
			echo "<pre class='dd object'>";
			echo "<span class='comment'>// {$lastCaller['file']}@{$lastCaller['calledIn']}: {$lastCaller['line']}</span>";
			echo "<br>" . null() . "<br>";
			echo "</pre>";
			echo "<br>";
		}
	}

	// FORMATTING FUNCTIONS //
	/**
	 * Includes the style for all the formatting functions.
	 */
	function includeStyle(): void
	{
		if ($ddStyleIncluded ?? false) return;

		echo ("<style>
			body {
				display: flex;
				flex-direction: column;

				margin: 0;
				padding: 0;
			}

			body .dd:first-child {
				margin-top: 0;
			}

			body .dd:last-child {
				margin-bottom: 0;
			}

			.dd {
				width: auto;
				max-width: 100vw;

				margin: .25rem 0;
				padding: 0.5rem;

				text-wrap: auto;
				white-space: pre-wrap;

				background-color: #111;
				color: dodgerblue;
			}

			.dd > pre {
				margin: 0;
				display: flex;
				flex-direction: column;
				text-wrap: auto;
			}

			.dd > pre.object,
			.dd .object {
				background-color: unset;
				color: unset;
			}

			.dd .object:not(.dd > .object) {
				background-color: #eee;
			}

			.dd .comment {
				color: #888;
			}

			.dd > .object {
				display: block;
			}

			.dd > .object,
			.dd .objKey,
			.dd .num {
				color: #00aeff;
			}

			.dd .content,
			.dd .string,
			.dd .key,
			.dd .value {
				color: #0f0;
			}

			.dd .content {
				display: flex;
				flex-direction: column;
			}

			.dd .boolean,
			.dd .string,
			.dd .value,
			.dd .null {
				font-weight: bold;
			}

			.dd .null {
				italic: true;
			}

			.dd .info {
				color: white;
			}

			.dd .qt,
			.dd .boolean,
			.dd .null,
			.dd .bracket,
			.dd .arrow {
				color: #f07d15;
			}

			.dd details > summary::marker {
				content: '';
				direction: ltr;
			}
			.dd details.array {
				--opening: '[';
				--closing: ']';
			}

			.dd details > summary::after {
				content: '▶';
				color: #777;
				display: inline-block;
			}
			.dd details[open] > summary::after {
				transform: rotate(90deg);
			}

			.dd details details {
				display: inline-block;
			}

			.dd details .detail-item {
				display: block;
			}

			.dd details .detail-item > * {
				display: inline-block;
				position: relative;
				vertical-align: top;
			}
		</style>");

		$ddStyleIncluded = true;
	}

	/**
	 * Returns a string of spaces.
	 *
	 * @param int $n The number of spaces to return.
	 *
	 * @return string A string of spaces.
	 */
	function space(int $n = 1): string
	{
		return str_repeat(" ", $n);
	}

	/**
	 * Returns a string of tabs.
	 *
	 * @param int $n The number of tabs to return.
	 *
	 * @return string A string of tabs.
	 */
	function tab(int $n = 1): string
	{
		return space($n * 2);
	}

	/**
	 * Returns a separator string that is pre-styled already. Its default type is an arrow
	 * but can be changed to a colon by passing the string `col` as the first argument.
	 *
	 * If the type provided is neither `arr` nor `col`, it will default to an arrow.
	 *
	 * `includeStyle` must be called before this function is used.
	 *
	 * @param string $type The type of separator to return.
	 *
	 * @return string A string of new lines.
	 */
	function separator(string $type = "arr"): string
	{
		return "<span class='arrow'>" . ($type == 'col' ? ":" : "=>") . "</span>";
	}

	/**
	 * Returns a quoted string that is pre-styled already. It is used to wrap strings in
	 * quotes and style them differently for better readability.
	 *
	 * `includeStyle` must be called before this function is used.
	 *
	 * @param string $str The string to wrap in quotes.
	 *
	 * @return string A string of new lines.
	 */
	function qt(string $str): string
	{
		return "<span class='qt'>\"</span>$str<span class='qt'>\"</span>";
	}

	/**
	 * Returns a string of new lines that is pre-styled already. It is used to add new lines
	 * to the output and style them differently for better readability.
	 *
	 * `includeStyle` must be called before this function is used.
	 *
	 * @param string $str The string to be made an info.
	 *
	 * @return string A string of new lines.
	 */
	function info(string $str): string
	{
		return "<span class='info'>$str</span>";
	}

	/**
	 * Returns a string of new lines that is pre-styled already. It is used to
	 * style keys of an array differently for better readability.
	 *
	 * `includeStyle` must be called before this function is used.
	 *
	 * @param string $str The string to be formatted as a key.
	 *
	 * @return string A string of new lines.
	 */
	function keyF(string $str): string
	{
		return "<span class='key'>$str</span>";
	}

	/**
	 * Returns a string of new lines that is pre-styled already. It is used to
	 * style values of an array differently for better readability.
	 *
	 * `includeStyle` must be called before this function is used.
	 *
	 * @param string $str The string to be formatted as a value.
	 *
	 * @return string A string of new lines.
	 */
	function value(string $str): string
	{
		return "<span class='value'>$str</span>";
	}

	/**
	 * Returns a string that represents a `null` value in the debug output.
	 *
	 * `includeStyle` must be called before this function is used.
	 *
	 * @return string A string that represents a `null` value.
	 */
	function null(): string
	{
		return "<span class='null'>null</span>";
	}

	/**
	 * Handles the formatting of common types such as strings, booleans, integers, doubles,
	 * and nulls.
	 *
	 * `$inf` is the **info** of the `$val` and usually serves as a key for the object that
	 * was passed in. If `$inf` is not provided, it will omit the key and just display or
	 * return the formatted value.
	 *
	 * If `$return` is set to true, it will return the formatted value instead of echoing it,
	 * allowing you to use it in other functions.
	 *
	 * @param mixed $val The value to format.
	 * @param string|null $inf The info of the value.
	 * @param bool $return Whether to return the formatted value or echo it.
	 */
	function handleCommonFormat(mixed $val, ?string $inf = null, $return = false): string
	{
		$type = gettype($val);

		$toRet = $inf == null ? "" : "<span class='content'>$inf</span>&nbsp;" . separator("col") . "&nbsp;";

		switch ($type) {
			case "string":
				$str = qt(trim($val, '"'));
				$toRet = "<span class='string'>$str</span>";
				break;

			case "boolean":
				$toRet = "<span class='boolean'>" . ($val ? "true" : "false") . "</span>";
				break;

			case "integer":
			case "double":
				$toRet = "<span class='num'>$val</span>";
				break;

			case "NULL":
				$toRet = null();
				break;

			case "array":
				$toRet = handleArrayFormat($val, true);
				break;

			case "object":
				$toRet = "<div class='object'>" . highlight_string("<?php\n" . var_export($val, true), true) . "</div>";
				break;

			default:
				$toRet = $val;
		}

		if ($return) return $toRet;
		echo $toRet;
		return '';
	}

	/**
	 * Handles the formatting of arrays to be displayed in the debug output. The function
	 * cleans the array and formats it in a way that is easy to read and understand.
	 * Furthermore, the array contents will be hidden by default and can be toggled to
	 * show by clicking on the summary, allowing for a cleaner and more organized output.
	 *
	 * If the array contains other arrays, it will recursively call itself to handle the
	 * nested arrays as well. If the array contains other types such as `strings`,
	 * `booleans`, `integers`, `doubles`, or `NULL``, it will call `handleCommonFormat`
	 * to format them, while also handling the nested arrays.
	 *
	 * When an `object` is encountered, it will be highlighted as a PHP code block to make
	 * it easier to read.
	 *
	 * `includeStyle` must be called before this function is used.
	 *
	 * @param array $arr The array to format.
	 * @param bool $return Whether to return the formatted value or echo it.
	 *
	 * @return string
	 */
	function handleArrayFormat(array $arr, bool $return = false): string
	{
		$toRet = "";

		$type = gettype($arr);
		$count = $type == "array" ? count($arr) : count((array) $arr);
		$bracket = $type == "array" ? ["[", "]"] : ["{", "}"];

		$toRet .= "<details class='$type'>";
		$toRet .=	"<summary><span class='objKey'>$type:$count</span> <span class='bracket'>$bracket[0]</span></summary>";
		$toRet .= 	"<div class='content'>";

		foreach ($arr as $k => $v) {
			$newKey = handleCommonFormat($k, null, true);
			$newVal = handleCommonFormat($v, null, true);

			$toRet .= "<div class='detail-item'>";
			$toRet .= tab() . $newKey . " " . separator() . " $newVal<br>";
			$toRet .= "</div>";

		}

		$toRet .=		"<span class='bracket'>$bracket[1]</span>";
		$toRet .= 	"</div>";
		$toRet .= "</details>";

		if ($return) return $toRet;
		echo $toRet;
		return '';
	}

	/**
	 * Removes a parameter from a URL and returns the new URL.
	 *
	 * @param string $url The URL to remove the parameter from.
	 * @param string|array $param The parameter(s) to remove.
	 *
	 * @return string The new URL without the parameter.
	 */
	function removeParams(string $url, string|array $param): string
	{
		if (is_string($param)) $param = [$param];

		foreach ($param as $p) {
			if (!is_string($p)) continue;

			$url = preg_replace('/&?' . $p . '=[^&]*/', '', $url);
			$url = preg_replace('/\?&/', '?', $url);
			$url = preg_replace('/\?$/', '', $url);
		}

		return $url;
	}

	/**
	 * Checks whether a URL has a certain parameter or not.
	 *
	 * @param string $url The URL to check.
	 * @param string $param The parameter to check for.
	 *
	 * @return bool Whether the URL has the parameter or not.
	 */
	function hasParam(string $url, string $param): bool
	{
		$queryParamsStr = parse_url($url, PHP_URL_QUERY);
		$queryParamsStr = empty($queryParamsStr) ? [] : explode('&', $queryParamsStr);

		$queryParams = [];
		foreach ($queryParamsStr as $qp) {
			$qp = explode('=', $qp);
			$queryParams[$qp[0]] = $qp[1];
		}

		return array_key_exists($param, $queryParams);
	}

	// RESPONSE FUNCTIONS //

	/**
	 * Sends a JSON response to the client.
	 * @param array $data The data to send.
	 * @param int $status The status code of the response. Default is 200.
	 * @param array $headers The headers to send.
	 *
	 * @return void
	 */
	function response(array $data, int $status = 200, array $headers = []): void
	{
		http_response_code($status);

		header('Content-Type: application/json; charset=utf-8');
		foreach ($headers as $key => $value)
			if ($key != "Content-Type" && $key != "charset")
				header("$key: $value");

		echo json_encode($data);
	}
