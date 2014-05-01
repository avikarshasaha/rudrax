<?php
class Notification_Controller extends Controller
{
        const MAX_MOVE_STACK_SIZE = 5000;
        
        static private $_uids = array();

        /**
	 * A queue of move events that have occurred.
	 *
	 * @static
	 * @access private
	 * @var    array
	 */
	static private $_commands = array();

	/**
	 * The current cursor position for this listener.
	 *
	 * @access private
	 * @var    float
	 */
	private $_cursor;

        private $_db;

        
        /**
	 * Constructor.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct(HTTPRequest $request, Dispatcher $dispatcher)
	{
            $this->_db = Lib_Db::getInstance();
            // Call the parent constructor.
            parent::__construct($request, $dispatcher);
	}

        /**
	 * Listens for updates from other connections.
	 *
	 * @access public
	 * @return void
	 */
	public function updates()
	{
                // If a cursor was passed in, make that our new cursor.
		$request_cursor = $this->request->get_request_var('waterspout_cursor');
		if (empty($this->_cursor) && !empty($request_cursor) && $request_cursor <= $this->_nextCursor())
		{
			$this->_cursor = $request_cursor;
		}
		// If the server doesn't have any commands in the stack yet, start the
		// cursor out at 0.
		elseif (!count(self::$_commands))
		{
			$this->_cursor = 0;
		}
		// If no cursor was passed in, then figure it out.
		elseif (is_null($this->_cursor))
		{
			$this->_cursor = (int) end(array_keys(self::$_commands)) + 1;
		}

		$this->dispatcher->add_listener($this);

        }
       
        /**
	 * Returns information about the number and type of requests made and the amount of
	 * data transfered.
	 *
	 * @access public
	 * @return void
	 */
	public function presence()
	{
                
                $this->_enqueue(array(
                   'senderUid' => $this->request->get_request_var('senderUid'),
                   'recipientUid' => $this->request->get_request_var('recipientUid')
                 ));
                
		$response = new HTTPResponse(200);

		// Determine if this is a JSONP request.
		$body = array('__URI__' => $this->uri);

		if ($this->request->get_request_var('callback')) {
                    $response->set_body($this->request->get_request_var('callback') . '(' . json_encode($body) . ');', false);
		} else {
                    $response->set_body($body, true);
		}
		$this->write($response);
        }

        private function _populateData(array $command)
        {
            $fetch = $this->_db->fetch("SELECT count(*) as count "
            . " FROM mycrysis_notification WHERE recipientUid = %d AND isNew = 1", $command['recipientUid']);
            return array("recipientUid" => $command['recipientUid'], "count" => $fetch->count);
            
        }

        public function process_event(Controller $mover = null)
	{

		$commands = $this->_dequeue();

		if (empty($commands)) {
			return;
		}

                $response = new HTTPResponse(200);
		$body = array('__URI__'  => $this->uri,
					  'cursor'   => $this->_nextCursor(),
                                          'data' => $this->_populateData(reset($commands))
					  );

		if ($this->request->get_request_var('callback')) {
                    $response->set_body($this->request->get_request_var('callback') . '(' . json_encode($body) . ');', false);
		} else {
                    $response->set_body($body, true);
		}
		$this->write($response);

                $this->_cursor = $this->_nextCursor();
        }


        /**
         * Push in the queue a command
         * 
         * @param array $command
         */
        private function _enqueue($command)
	{
            self::$_commands[] = $command;
            self::$_commands = array_slice(self::$_commands, -self::MAX_MOVE_STACK_SIZE, self::MAX_MOVE_STACK_SIZE, true);
        }
        /**
         * Get next cursor position for the queue
         *
         * @return int
         */
        private function _nextCursor()
	{
            return (int) end(array_keys(self::$_commands)) + 1;
        }
        /**
         * Extract event commands of the client from the common queue
         *
         * @return array
         */
        private function _dequeue()
	{
            $key = array_search((int) $this->_cursor, array_keys(self::$_commands));
            if ($key === false && !is_null($this->_cursor)) {
                return;
            }
            return array_slice(self::$_commands, $key);
        }

        
}