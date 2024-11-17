<?php
namespace ProcessPhp;

use BadMethodCallException;
use Exception;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionMethod;
use mysqli;

const SELECT = 0;
const INSERT = 1;
const UPDATE = 2;
const DELETE = 3;

/**
 * A class that generates SQL queries using the Query Builder pattern. This class
 * is designed to be used with the MySQLi extension.
 *
 * If the global variable `$conn` is not set, the class will create a new connection
 * to the database using the default parameters. However, these parameters can be
 * changed by passing the `host`, `username`, `password`, and `database` name to the constructor.
 *
 * The class is designed to be used both statically and dynamically. The class can be used
 * statically by calling any function directly. However, the class can also be used dynamically
 * by calling the `query` function and chaining the functions.
 */
class Query
{
	/**
	 * Connection to the database.
	 *
	 * @var mysqli
	 */
	private ?mysqli $conn = null;

	/**
	 * The query to be executed.
	 *
	 * @var string
	 */
	private string $query = "";

	/**
	 * The table to be queried. The table will
	 * be split into the table name and the alias,
	 * if present.
	 *
	 * The resulting array will look like this:
	 * ```php
	 * [
	 * 	"table" => "table_name",
	 * 	"alias" => "alias_name"
	 * ]
	 * ```
	 *
	 * @var string
	 */
	private array $table = [];

	/**
	 * The columns to be queried.
	 *
	 * @var array
	 */
	private array $select = [];

	/**
	 * The columns to be queried. This array contains another array, containing
	 * the column name along with its operator and value.
	 *
	 * @var array
	 */
	private array $where = [];

	/**
	 * The joins to be queried. This array contains another array, containing
	 * the table to join, the conditions to join, and the type of join.
	 *
	 * @var array
	 */
	private array $joins = [];

	/**
	 * The columns of the said table. This array updates whenever
	 * the `table` function is called. Or as needed.
	 *
	 * @var array
	 */
	private array $columns = [];

	/**
	 * The columns to order by. This array contains another array, containing
	 * the column name and the direction of the order.
	 *
	 * @var array
	 */
	private array $orderBy = [];

	/**
	 * The columns to insert. This array contains another array, containing
	 * the column name and the value to insert.
	 *
	 * @var array
	 */
	private array $insert = [];

	/**
	 * The columns to update. This array contains another array, containing
	 * the column name and the value to update.
	 *
	 * @var array
	 */
	private array $update = [];

	/**
	 * Defines the primary key of the table. This variable is used to identify
	 * the primary key of the table and is used for updating and deleting records.
	 *
	 * This variable will be set when the `table` function is called.
	 */
	private string $primaryKey = "";

	// CONSTRUCTOR //
	/**
	 * Creates a new instance of the Query class that could be used to generate SQL queries and
	 * execute them using the MySQLi extension.
	 *
	 * @param string $host The host of the database.
	 * @param string $username The username of the database.
	 * @param string $password The password of the database.
	 * @param string $database The name of the database.
	 */
	public function __construct(string $host = "localhost", string $username = "root", string $password = "", string $database = "fmj_electronics")
	{
		global $conn;

		if (empty($conn))
			$this->conn = new mysqli($host, $username, $password, $database);
		else
			$this->conn = $conn;
	}

	// SQL BUILDER FUNCTIONS //
	/**
	 * Sets the table to be queried. The table can also be given an alias by adding
	 * `AS alias` after the table name.
	 *
	 * @param string $table The table to be queried.
	 *
	 * @return Query
	 */
	protected function table(string $table): Query
	{
		$table = trim($table);
		$table = explode(" ", $table);
		$newTable = [];

		switch (count($table)) {
			case 2:
				$newTable["alias"] = $table[1];
			case 3:
				$newTable["alias"] = $table[2];
			case 1:
				$newTable["table"] = $table[0];
		}

		$this->table = $newTable;
		$this->updateTableCols();

		return $this;
	}

	/**
	 * Generates a SELECT query, selecting all the columns from the table.
	 *
	 * If specific columns are needed, use the `select` function.
	 *
	 * @return Query
	 */
	protected function selectAll(): Query
	{
		array_push($this->select, ["col" => "*"]);
		return $this;
	}

	/**
	 * Adds a column to the SELECT query. The columns can be given an alias by
	 * simply adding `AS alias` after the column name. Aggregates can also be
	 * used in the columns by using the format `aggregate(column) AS alias`.
	 *
	 * The `fields` parameter can be an array of columns or a string of columns.
	 * If the `fields` parameter is a string, the function will assume that the
	 * string is a single column. If multiple columns are needed, use the array
	 * format.
	 *
	 * Duplicated queries will be removed.
	 *
	 * @param array|string $fields The columns to select.
	 *
	 * @return Query
	 */
	protected function select(array|string $fields): Query
	{
		if (is_string($fields)) $fields = [$fields];

		if (empty($fields)) {
			throw new Exception("Missing parameter: fields");
		}

		// Pre-processes the fields to identify which are columns and which are aliases.
		$toMerge = [];
		foreach ($fields as $f) {
			$f = explode(" ", $f);
			$len = count($f);
			$newFields = [];

			if ($len > 0) {
				$colTarget = preg_split("/\./", $f[0]);

				if (count($colTarget) == 2) {
					$newFields["col"] = "`{$colTarget[0]}`.`{$colTarget[1]}`";
				} else {
					$newFields["col"] = "`$f[0]`";
				}

				if ($len > 1) {
					$newFields["alias"] = $f[1];

					if ($len > 2) {
						$newFields["alias"] = $f[2];
					}
				}

				$toMerge = array_merge($toMerge, [$newFields]);
			}
		}

		$this->select = array_merge($this->select, $toMerge);
		return $this;
	}

	/**
	 * Adds a raw query to the SELECT query. The raw query should be a string
	 * containing the query to be executed. The function will not validate the
	 * query and will be added as is.
	 *
	 * @param string $query The raw query to be executed.
	 *
	 * @return Query
	 */
	protected function selectRaw(string $query): Query
	{
		array_push($this->select, ["col" => $query]);
		return $this;
	}

	/**
	 * Generates a LEFT JOIN query with the specified table and links. The `links` are
	 * an array of columns to compare. The function will assume that the operator is
	 * `=` and the conjunction is `AND` if they are not present in the query.
	 *
	 * The `links` parameter should always be an array of arrays. The inner array
	 * should contain the columns to compare. An example of the `links` parameter
	 * is as follows:
	 *
	 * ```php
	 * $links = [
	 * 	["id", "user_id"],				// id = user_id
	 * 	["name", "username", "OR"]			// OR name = username
	 * 	["DATE(comment_date)", ">", "NOW()", "AND"]	// AND DATE(comment_date) > NOW()
	 * ];
	 * ```
	 *
	 * In full context with the `table` parameter, the query would look like this:
	 *
	 * ```php
	 * $query->table("users")->leftJoin("comments", $links);
	 * ```
	 *
	 * The query would look like this:
	 *
	 * ```sql
	 * SELECT * FROM users LEFT JOIN comments ON id = user_id OR name = username AND DATE(comment_date) > NOW();
	 * ```
	 *
	 * @param string $table The table to join.
	 * @param array $links The columns to compare.
	 *
	 * @return Query
	 */
	protected function leftJoin(string $table, array $links): Query
	{
		// Checks the parameters if they are empty.
		foreach (self::getFunctionParams('leftJoin') as $arg) {;
			if (empty(${$arg})) {
				throw new Exception("Missing parameter: {$arg}");
			}
		}

		foreach ($links as $link) {
			$link = self::joinPreProcess($link);

			// Condition Query Validation
			$len = count($link);
			$exception = false;
			if ($len == 0) $exception = new Exception("Left join is missing all columns.");
			else if ($len == 1) $exception = new Exception("Left join is missing one column.");
			if ($exception) throw $exception;

			array_push($this->joins, [
				"table" => $table,
				"conditions" => $link,
				"type" => "LEFT"
			]);
		}

		return $this;
	}

	/**
	 * Generates a RIGHT JOIN query with the specified table and links. The `links` are
	 * an array of columns to compare. The function will assume that the operator is
	 * `=` and the conjunction is `AND` if they are not present in the query.
	 *
	 * The `links` parameter should always be an array of arrays. The inner array
	 * should contain the columns to compare. An example of the `links` parameter
	 * is as follows:
	 *
	 * ```php
	 * $links = [
	 * 	["id", "user_id"],				// id = user_id
	 * 	["name", "username", "OR"]			// OR name = username
	 * 	["DATE(comment_date)", ">", "NOW()", "AND"]	// AND DATE(comment_date) > NOW()
	 * ];
	 * ```
	 *
	 * In full context with the `table` parameter, the query would look like this:
	 *
	 * ```php
	 * $query->table("users")->rightJoin("comments", $links);
	 * ```
	 *
	 * The query would look like this:
	 *
	 * ```sql
	 * SELECT * FROM users RIGHT JOIN comments ON id = user_id OR name = username AND DATE(comment_date) > NOW();
	 * ```
	 *
	 * @param string $table The table to join.
	 * @param array $links The columns to compare.
	 *
	 * @return Query
	 */
	protected function rightJoin(string $table, array $links): Query
	{
		// Checks the parameters if they are empty.
		foreach (self::getFunctionParams('rightJoin') as $arg) {;
			if (empty(${$arg})) {
				throw new Exception("Missing parameter: {$arg}");
			}
		}

		foreach ($links as $link) {
			$link = self::joinPreProcess($link);

			// Condition Query Validation
			$len = count($link);
			$exception = false;
			if ($len == 0) $exception = new Exception("Right join is missing all columns.");
			else if ($len == 1) $exception = new Exception("Right join is missing one column.");
			if ($exception) throw $exception;

			array_push($this->joins, [
				"table" => $table,
				"conditions" => $link,
				"type" => "RIGHT"
			]);
		}

		return $this;
	}

	/**
	 * Generates a INNER JOIN query with the specified table and links. The `links` are
	 * an array of columns to compare. The function will assume that the operator is
	 * `=` and the conjunction is `AND` if they are not present in the query.
	 *
	 * The `links` parameter should always be an array of arrays. The inner array
	 * should contain the columns to compare. An example of the `links` parameter
	 * is as follows:
	 *
	 * ```php
	 * $links = [
	 * 	["id", "user_id"],				// id = user_id
	 * 	["name", "username", "OR"]			// OR name = username
	 * 	["DATE(comment_date)", ">", "NOW()", "AND"]	// AND DATE(comment_date) > NOW()
	 * ];
	 * ```
	 *
	 * In full context with the `table` parameter, the query would look like this:
	 *
	 * ```php
	 * $query->table("users")->innerJoin("comments", $links);
	 * ```
	 *
	 * The query would look like this:
	 *
	 * ```sql
	 * SELECT * FROM users INNER JOIN comments ON id = user_id OR name = username AND DATE(comment_date) > NOW();
	 * ```
	 *
	 * @param string $table The table to join.
	 * @param array $links The columns to compare.
	 *
	 * @return Query
	 */
	protected function innerJoin(string $table, array $links): Query
	{
		// Checks the parameters if they are empty.
		foreach (self::getFunctionParams('innerJoin') as $arg) {;
			if (empty(${$arg})) {
				throw new Exception("Missing parameter: {$arg}");
			}
		}

		foreach ($links as $link) {
			$link = self::joinPreProcess($link);

			// Condition Query Validation
			$len = count($link);
			$exception = false;
			if ($len == 0) $exception = new Exception("Inner join is missing all columns.");
			else if ($len == 1) $exception = new Exception("Inner join is missing one column.");
			if ($exception) throw $exception;

			array_push($this->joins, [
				"table" => $table,
				"conditions" => $link,
				"type" => "INNER"
			]);
		}

		return $this;
	}

	/**
	 * Generates a OUTER JOIN query with the specified table and links. The `links` are
	 * an array of columns to compare. The function will assume that the operator is
	 * `=` and the conjunction is `AND` if they are not present in the query.
	 *
	 * The `links` parameter should always be an array of arrays. The inner array
	 * should contain the columns to compare. An example of the `links` parameter
	 * is as follows:
	 *
	 * ```php
	 * $links = [
	 * 	["id", "user_id"],				// id = user_id
	 * 	["name", "username", "OR"]			// OR name = username
	 * 	["DATE(comment_date)", ">", "NOW()", "AND"]	// AND DATE(comment_date) > NOW()
	 * ];
	 * ```
	 *
	 * In full context with the `table` parameter, the query would look like this:
	 *
	 * ```php
	 * $query->table("users")->outerJoin("comments", $links);
	 * ```
	 *
	 * The query would look like this:
	 *
	 * ```sql
	 * SELECT * FROM users OUTER JOIN comments ON id = user_id OR name = username AND DATE(comment_date) > NOW();
	 * ```
	 *
	 * @param string $table The table to join.
	 * @param array $links The columns to compare.
	 *
	 * @return Query
	 */
	protected function outerJoin(string $table, array $links): Query
	{
		// Checks the parameters if they are empty.
		foreach (self::getFunctionParams('outerJoin') as $arg) {;
			if (empty(${$arg})) {
				throw new Exception("Missing parameter: {$arg}");
			}
		}

		foreach ($links as $link) {
			$link = self::joinPreProcess($link);

			// Condition Query Validation
			$len = count($link);
			$exception = false;
			if ($len == 0) $exception = new Exception("Outer join is missing all columns.");
			else if ($len == 1) $exception = new Exception("Outer join is missing one column.");
			if ($exception) throw $exception;

			array_push($this->joins, [
				"table" => $table,
				"conditions" => $link,
				"type" => "OUTER"
			]);
		}

		return $this;
	}

	/**
	 * Generates a WHERE query with the specified column, operator, and value. If
	 * the `value` parameter is empty, the function will assume that the operator
	 * is `=` and the value is the second parameter.
	 *
	 * If an aggregate is to be used, then use the `selectRaw` function.
	 *
	 * @param string $column The column to query.
	 * @param string $operator The operator to use.
	 * @param string|null $value The value to compare.
	 *
	 * @return Query
	 *
	 * @throws Exception If any of the parameters are empty.
	 */
	protected function where(string $column, string $operator, ?string $value = null): Query
	{
		if (empty($value)) {
			$value = $operator;
			$operator = "=";
		}

		// Checks the parameters if they are empty.
		foreach (self::getFunctionParams('where') as $arg) {
			if (empty(${$arg})) {
				throw new Exception("Missing parameter: {$arg}");
			}
		}

		return $this->whereRaw("{$column} {$operator} {$value}");
	}

	/**
	 * Generates a WHERE query but with an OR operator, with the specified column,
	 * operator, and value. If the `value` parameter is empty, the function will
	 * assume that the operator is `=` and the value is the second parameter.
	 *
	 * * If an aggregate is to be used, then use the `selectRaw` function.
	 *
	 * @param string $column The column to query.
	 * @param string $operator The operator to use.
	 * @param string $value The value to compare.
	 *
	 * @return Query
	 *
	 * @throws Exception If any of the parameters are empty.
	 */
	protected function orWhere(string $column, string $operator, ?string $value = null): Query
	{
		if (empty($value)) {
			$value = $operator;
			$operator = "=";
		}

		// Checks the parameters if they are empty.
		foreach (self::getFunctionParams('orWhere') as $arg) {;
			if (empty(${$arg})) {
				throw new Exception("Missing parameter: {$arg}");
			}
		}

		return $this->orWhereRaw("{$column} {$operator} {$value}");
	}

	/**
	 * Adds a raw query to the WHERE query. The raw query should be a string
	 * containing the query to be executed. The function will lightly validate
	 * the query and will add it only if it has a column, operator, and value.
	 *
	 * This `whereRaw` function is for `AND` conditions only. If an `OR` condition
	 * is needed, use the `orWhereRaw` function.
	 *
	 * The raw query should be in the format of `<column|alias> <operator> <value>`.
	 *
	 * @param string $query The raw query to be executed.
	 *
	 * @return Query
	 */
	protected function whereRaw(string $query): Query
	{
		$query = trim($query);
		$split = explode(" ", $query);
		$len = count($split);
		$newQuery = [];

		$aggregates = ["COUNT", "SUM", "AVG", "MIN", "MAX"];

		if ($len > 0) {
			$hasAggregate = false;
			foreach ($aggregates as $aggregate) {
				$matches = [];

				// Checks for aggregates in the query.
				if (preg_match("/\b(?<agg>$aggregate)\b\(`?(?<col>[\w\.]+)`?\)/", $split[0], $matches)) {
					$colTarget = preg_split("/\./", $matches["col"]);
					$hasAggregate = true;

					if (count($colTarget) == 2) {
						$split[0] = "$matches[agg](`{$colTarget[0]}`.`{$colTarget[1]}`)";
					} else {
						$split[0] = $aggregate . '(`' . trim(trim($colTarget[0], "`")) . '`)';
					}

					$newQuery["col"] = "{$split[0]}";
				}
			}

			if (!$hasAggregate) {
				$colTarget = preg_split("/\./", $split[0]);
				if (count($colTarget) == 2) {
					$split[0] = "`{$colTarget[0]}`.`{$colTarget[1]}`";
				} else {
					$split[0] = "`{$split[0]}`";
				}
			}

			if ($len == 3) {
				$newQuery["col"] = "{$split[0]}";
				$newQuery["ops"] = "{$split[1]}";
				$newQuery["val"] = "'{$split[2]}'";
				$newQuery["con"] = "AND";
			}
		} else {
			throw new Exception("Malformed query: {$query}");
		}

		array_push($this->where, $newQuery);
		return $this;
	}

	/**
	 * Adds a raw query to the WHERE query. This function is similar to the `whereRaw`
	 * but is used exclusively for `OR` conditions. If an `AND` condition is needed,
	 * The `whereRaw` function should be used.
	 *
	 * The raw query should be a string containing the query to be executed. The
	 * function will lightly validate the query and will add it only if it has a
	 * column, operator, and value.
	 *
	 * The raw query should be in the format of `<column|alias> <operator> <value>`.
	 *
	 * @param string $query The raw query to be executed.
	 *
	 * @return Query
	 */
	protected function orWhereRaw(string $query): Query
	{
		$query = trim($query);
		$split = explode(" ", $query);
		$len = count($split);
		$newQuery = [];

		$aggregates = ["COUNT", "SUM", "AVG", "MIN", "MAX"];

		if ($len > 0) {
			foreach ($aggregates as $aggregate) {
				$matches = [];

				// Checks for aggregates in the query.
				if (preg_match("/\b(?<agg>$aggregate)\b\(`?(?<col>[\w\.]+)`?\)/", $split[0], $matches)) {
					$colTarget = preg_split("/\./", $matches["col"]);

					if (count($colTarget) == 2) {
						$split[0] = "$matches[agg](`{$colTarget[0]}`.`{$colTarget[1]}`)";
					} else {
						$split[0] = $aggregate . '(`' . trim(trim($colTarget[0], "`")) . '`)';
					}

					$newQuery["col"] = "{$split[0]}";
				}
			}

			if ($len == 3) {
				$newQuery["col"] = "`{$split[0]}`";
				$newQuery["ops"] = "{$split[1]}";
				$newQuery["val"] = "'{$split[2]}'";
				$newQuery["con"] = "OR";
			}
		} else {
			throw new Exception("Malformed query: {$query}");
		}

		array_push($this->where, $newQuery);
		return $this;
	}

	/**
	 * Defines an ORDER BY clause in the query. The function will assume that the
	 * direction is `DESC` if the `direction` parameter is empty.
	 *
	 * The `column` parameter can also contain dots to signify that the column is
	 * from a different table. The function will automatically put backticks on the
	 * column name if there are dots in the column name.
	 *
	 * @param string $column The column to order by.
	 * @param string $direction The direction of the order.
	 *
	 * @return Query
	 */
	protected function orderBy(string $column, string $direction = "DESC"): Query
	{
		$direction = strtoupper($direction);

		if ($direction != "ASC" && $direction != "DESC") {
			throw new InvalidArgumentException("Invalid direction: {$direction}");
		}

		// Pre-process the column to put backticks if there are dots in the column name.
		$matches = preg_split("/\./", $column);
		if (count($matches) == 2) {
			$column = "`{$matches[0]}`.`{$matches[1]}`";
		} else {
			$column = "`{$column}`";
		}

		array_push($this->orderBy, ["col" => $column, "dir" => $direction]);
		return $this;
	}

	/**
	 * Generates an INSERT query with the specified column and value. The function
	 * will always make the values a string. If the value is anything other than a
	 * string, the function will convert it to a string by simply enclosing it with
	 * single quotes.
	 */
	protected function insert(string $column, mixed $value): Query
	{
		// Checks the parameters if they are empty.
		foreach (self::getFunctionParams('insert') as $arg) {;
			if (empty(${$arg}) && ($arg == "value" && !is_numeric($value))) {
				throw new Exception("Missing parameter: {$arg}");
			}
		}

		// Pre-process the column to put backticks if there are dots in the column name.
		$matches = preg_split("/\./", $column);
		if (count($matches) == 2) {
			$column = "`{$matches[0]}`.`{$matches[1]}`";
		} else {
			$column = "`{$column}`";
		}

		array_push($this->insert, ["col" => $column, "val" => $value]);

		return $this;
	}

	/**
	 * Generates an UPDATE query with the specified column and value. The function
	 * will always make the values a string. If the value is anything other than a
	 * string, the function will convert it to a string by simply enclosing it with
	 * single quotes.
	 */
	protected function update(string $column, mixed $value): Query
	{
		// Checks the parameters if they are empty.
		foreach (self::getFunctionParams('update') as $arg) {
			if (empty(${$arg}) && ($arg == "value" && !is_numeric($value))) {
				throw new Exception("Missing parameter: {$arg}");
			}
		}

		// Pre-process the column to put backticks if there are dots in the column name.
		$matches = preg_split("/\./", $column);
		if (count($matches) == 2) {
			$column = "`{$matches[0]}`.`{$matches[1]}`";
		} else {
			$column = "`{$column}`";
		}

		array_push($this->update, ["col" => $column, "val" => $value]);

		return $this;
	}

	// QUERY EXECUTION FUNCTIONS //
	/**
	 * Builds the query based on the functions called. The function will check if
	 * the `table` function is called first before building the query. If the `table`
	 * function is not called, the function will throw an exception.
	 *
	 * Once the query is built, the function will execute the `SELECT` query and get
	 * the results.
	 *
	 * The results will be returned as an array of objects.
	 *
	 * @return array
	 */
	protected function get(): array
	{
		$this->buildQuery(SELECT);

		$result = $this->conn->query($this->query);
		$data = $result->fetch_all(MYSQLI_ASSOC);

		// Convert the data to an object
		$data = array_map(fn ($r) => (object) $r, $data);

		return $data ?? [];
	}

	/**
	 * Builds the query then returns the built query.
	 *
	 * `$target` is the type of query to build. The available types are:
	 * - 0: SELECT
	 * - 1: INSERT
	 * - 2: UPDATE
	 * - 3: DELETE
	 *
	 * By default, the function will build a SELECT query.
	 *
	 * @return string
	 */
	protected function sql(int $target = SELECT): string
	{
		$this->buildQuery($target);
		return $this->query;
	}

	/**
	 * Builds the query based on the functions called. The function will check if
	 * the `table` function is called first before building the query. If the `table`
	 * function is not called, the function will throw an exception.
	 *
	 * Once the query is built, the function will execute the `INSERT` query and
	 * return the result.
	 *
	 * The result will be a boolean value that determines whether the query was
	 * successful or not.
	 *
	 * @return bool
	 */
	protected function push(): bool
	{
		$this->buildQuery(INSERT);
		try {
			$this->conn->begin_transaction();

			$result = $this->conn->query($this->query);

			$this->conn->commit();
		} catch (Exception $e) {
			response([
				"message" => $e->getMessage(),
				"query" => $this->query,
				"sqlError" => $this->error()
			], 500);
			return false;
		}
		return $result;
	}

	/**
	 * Builds the query based on the functions called. The function will check if
	 * the `table` function is called first before building the query. If the `table`
	 * function is not called, the function will throw an exception.
	 *
	 * Once the query is built, the function will execute the `UPDATE` query and return
	 * the result.
	 *
	 * The result will be a boolean value that determines whether the query was
	 * successful or not.
	 *
	 * @return bool
	 */
	protected function apply(): bool
	{
		$this->buildQuery(UPDATE);
		try {
			$this->conn->begin_transaction();

			$result = $this->conn->query($this->query);

			$this->conn->commit();
		} catch (Exception $e) {
			response([
				"message" => $e->getMessage(),
				"query" => $this->query,
				"sqlError" => $this->error()
			], 500);
			return false;
		}
		return $result;
	}

	// HELPER FUNCTIONS //
	/**
	 * Fetches the parameters of the specified function.
	 *
	 * @param string $fnName The name of the function.
	 *
	 * @return array
	 *
	 * @throws ReflectionException If the function does not exist.
	 */
	private static function getFunctionParams(string $fnName): array
	{
		$class = new ReflectionClass(Query::class);
		$method = $class->getMethod($fnName);
		$params = $method->getParameters();
		return array_map(fn ($param) => $param->name, $params);
	}

	/**
	 * Fetches the names of the functions in the QueryBuilder class.
	 *
	 * @return array
	 */
	private static function getFunctionNames(): array
	{
		$functionsObj = (new ReflectionClass(Query::class))
			->getMethods(ReflectionMethod::IS_PROTECTED);

		return array_map(function ($fn) {
			return $fn->name;
		}, $functionsObj);
	}

	/**
	 * Cleans the join's query conditions and provides a key-value pair for the
	 * column and value. If the operator and conjunction are not present, the
	 * function will assume that the operator is `=` and the conjunction is `AND`.
	 *
	 * Furthermore, the function will also check if the operator and conjunction
	 * are present in the query. If not, the function will add the default values
	 * for the operator and conjunction.
	 *
	 * Handling the columns for the conditions isn't part of this function and will
	 * return the array regardless if the `col1` and `col2` are present.
	 *
	 * The parts of the condition is as follows:
	 * - `col1` The first column to compare.	(i.e. `id`)
	 * - `op` The operator to use.				(i.e. `=`)
	 * - `col2` The second column to compare.	(i.e. `user_id`)
	 * - `con` The conjunction to use.			(i.e. `AND`)
	 *
	 * Using the example above, the query would look like this:
	 * `AND id = user_id`
	 *
	 * More examples can be seen in the documentation of the `leftJoin` and `rightJoin`
	 * functions.
	 *
	 * @param array $link The link to clean.
	 *
	 * @return array
	 */
	private static function joinPreProcess(array $link): array
	{
		$operators = [
			"=", "<", ">", "<=", ">=", "<>", "!=",
			"LIKE", "NOT LIKE", "IN", "NOT IN",
			"BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL",
			"EXISTS", "NOT EXISTS"
		];
		$conjunctions = ["AND", "OR"];

		$newLink = [];
		$link = array_values($link);
		$isOpPresent = false;
		$isConPresent = false;

		foreach ($link as $val) {
			if (in_array($val, $operators))
				$isOpPresent = true;

			if (in_array($val, $conjunctions))
				$isConPresent = true;
		}

		if (!$isOpPresent) {
			$newLink["op"] = "=";
		} else {
			foreach ($operators as $op) {
				$index = array_search($op, $link);

				if ($index) {
					$newLink["op"] = $op;
					unset($link[$index]);
					break;
				}
			}
		}

		if (!$isConPresent) {
			$newLink["con"] = "AND";
		} else {
			foreach ($conjunctions as $con) {
				$index = array_search($con, $link);
				if ($index) {
					$newLink["con"] = $con;
					unset($link[$index]);
					break;
				}
			}
		}

		$link = array_values($link);
		$len = count($link);
		if ($len > 0) {
			$newLink["col1"] = $link[0];

			if ($len > 1) {
				$newLink["col2"] = $link[1];
			}

			// Properly formats the column name for column 1.
			$matches = preg_split("/\./", $link[0]);
			if (count($matches) == 2) {
				$newLink["col1"] = "`{$matches[0]}`.`{$matches[1]}`";
			} else {
				$newLink["col1"] = "`{$link[0]}`";
			}

			// Properly formats the column name for column 2.
			$matches = preg_split("/\./", $link[1]);
			if (count($matches) == 2) {
				$newLink["col2"] = "`{$matches[0]}`.`{$matches[1]}`";
			} else {
				$newLink["col2"] = "`{$link[1]}`";
			}
		}
		return $newLink;
	}

	/**
	 * Builds the query using the parameters set by the user. The function will
	 * generate the query using the `SELECT`, `FROM`, `JOIN`, and `WHERE` clauses.
	 *
	 * To get the query, use the `sql` function.
	 *
	 * @param int $target The target of the query. The default is `SELECT`.
	 */
	private function buildQuery(int $target = SELECT): void
	{
		$query = "";

		if ($target == SELECT) {
			$query = "SELECT ";

			// SELECT
			if (empty($this->select)) {
				$query .= "*";
			} else {
				foreach ($this->select as $select) {
					// dump($select);
					$selectQuery = "{$select['col']}";

					if (!empty($select['alias'])) {
						$selectQuery .= " AS `{$select['alias']}`";
					}

					// dump($selectQuery);
					$query .= $selectQuery . ", ";
				}
				$query = rtrim($query, ", ");
			}

			// FROM
			$query .= " FROM `{$this->table['table']}`";
			// ALIAS
			if (!empty($this->table['alias'])) {
				$query .= " AS `{$this->table['alias']}`";
			}

			// JOINS
			if (!empty($this->joins)) {
				$joinQuery = "";

				$first = false;
				foreach ($this->joins as $join) {
					if (!$first) {
						$first = true;
						$join['conditions']['con'] = "";
					}

					$joinQuery .= " {$join['type']} JOIN {$join['table']} ON ";
					$conditions = $join['conditions'];

					$joinQueryCond = "{$conditions['con']} {$conditions['col1']} {$conditions['op']} {$conditions['col2']}";
					$joinQueryCond = trim(ltrim(ltrim($joinQueryCond, "AND "), " OR"));

					$joinQuery .= " $joinQueryCond";
				}

				$query .= " " . trim($joinQuery);
			}

			// WHERE
			if (!empty($this->where)) {
				$query .= " WHERE ";
				$whereQuery = "";

				foreach ($this->where as $where) {
					$whereQuery .= " {$where['con']} {$where['col']} {$where['ops']} {$where['val']}";
				}

				$whereQuery = trim(ltrim(ltrim($whereQuery, "AND "), " OR"));
				$query .= " $whereQuery";
			}

			// ORDER BY
			if (!empty($this->orderBy)) {
				$query .= " ORDER BY ";

				foreach ($this->orderBy as $orderBy) {
					$query .= "{$orderBy['col']} {$orderBy['dir']}, ";
				}

				$query = rtrim($query, ", ");
			}
		}
		else if ($target == INSERT) {
			$query = "INSERT INTO `{$this->table['table']}` (";

			$cols = "";
			$values = "";

			foreach ($this->insert as $insert) {
				$cols .= "{$insert['col']}, ";
				$values .= "'{$insert['val']}', ";
			}

			$cols = rtrim($cols, ", ");
			$values = rtrim($values, ", ");

			$query .= "$cols) VALUES ($values)";
		}
		else if ($target == UPDATE) {
			$query = "UPDATE `{$this->table['table']}` SET ";

			$set = "";
			foreach ($this->update as $update) {
				$set .= "{$update['col']} = '{$update['val']}', ";
			}

			$set = rtrim($set, ", ");
			$query .= "$set";

			// WHERE
			if (!empty($this->where)) {
				$query .= " WHERE ";
				$whereQuery = "";

				foreach ($this->where as $where) {
					$whereQuery .= " {$where['con']} {$where['col']} {$where['ops']} {$where['val']}";
				}

				$whereQuery = trim(ltrim(ltrim($whereQuery, "AND "), " OR"));
				$query .= " $whereQuery";
			}
		}
		else if ($target == DELETE) {
		}

		$this->query = $query;
	}

	/**
	 * Updates the columns of the table. The function will fetch the columns of the
	 * table and store them in the `columns` property. Additionally, the function
	 * will also fetch the primary key of the table and store it in the `primaryKey`
	 * property.
	 *
	 * If the table is not set, the function will throw an exception.
	 *
	 * @return Query
	 *
	 * @throws BadMethodCallException If the table is not set.
	 */
	private function updateTableCols(): Query
	{
		// Checks if the table is set. Throws an exception if the table is not set.
		if (empty($this->table)) throw new BadMethodCallException("Table is not set.");

		$this->columns = $this->conn
			->query("DESC `" . $this->table['table'] . "`")
			->fetch_all(MYSQLI_ASSOC);

		$this->columns = array_map(fn($i) => $i['Field'], $this->columns);

		// Defines the primary key of the table.
		$this->primaryKey = $this->conn
			->query("SHOW KEYS FROM `" . $this->table['table'] . "` WHERE Key_name = 'PRIMARY'")
			->fetch_assoc()['Column_name'];

		return $this;
	}

	/**
	 * Fetches the columns of the specified table. If the table is not set, the
	 * function will throw an exception.
	 *
	 * If the columns are already set, the function will return the columns.
	 *
	 * @param string|null $table The table to fetch the columns from.
	 *
	 * @return array
	 *
	 * @throws Exception If the table is not set.
	 */
	public function getColumns(?string $table = null): array
	{
		if (empty($this->columns)) {
			if (empty($this->table['table'])) {
				if (empty($table)) {
					throw new Exception("Table is not set.");
				} else {
					$this->table($table);
				}
			}

			$this->updateTableCols();
		}
		return $this->columns;
	}

	/**
	 * Fetches the primary key of the specified table. If the table is not set, the
	 * function will throw an exception.
	 *
	 * @return string
	 */
	public function pk(): string
	{
		if (empty($this->primaryKey)) {
			$this->updateTableCols();
		}
		return $this->primaryKey;
	}

	/**
	 * Fetches the error message from the connection.
	 *
	 * The function will return an object with the following properties:
	 * - `errorCode` The error code of the error.
	 * - `error` The error message.
	 * - `fullError` The full error message.
	 *
	 * @return string
	 */
	public function error(): object
	{
		$code = $this->conn->errno;
		$fullError = $this->conn->error;
		$msg = $fullError;

		if (empty($msg)) {
			$msg = "No error message.";
		}
		else if ($code == 1062) {
			$msg = "Duplicate entry.";
		}

		return (object) [
			"errorCode" => $code,
			"error" => $msg,
			"fullError" => $fullError
		];
	}

	// MAGIC METHODS //
	public function __call($method, $args)
	{
		if (in_array($method, self::getFunctionNames())) {
			return $this->$method(...$args);
		}
		else if ($method == "query") {
			$this->query = "";
			$this->select = [];
			$this->joins = [];
			$this->where = [];
			$this->orderBy = [];
			$this->insert = [];

			return $this;
		}

		throw new BadMethodCallException("Method \"{$method}\" does not exist.");
	}

	public static function __callStatic($method, $args)
	{
		if (in_array($method, self::getFunctionNames())) {
			return (new Query)->$method(...$args);
		}
		else if ($method == "query") {
			return new Query;
		}

		throw new BadMethodCallException("Method \"{$method}\" does not exist.");
	}
}
