<?php

/**
 * This is a script that creates campaigns & donations.
 *
 * It's designed to be called with wp-cli via the command line.
 *
 * wp eval-file charitable-spawn-data <donations> <campaigns>
 */

class Charitable_Data_Spawn {

    private $donations_count = 100;
    private $campaigns_count = 5;
    private $people = array(
        'Lea Corbin',
        'Steve Austin',
        'Arnold Etchison',
        'Axel Asher',
        'Max Crandall',
        'Larry Jordan',
        'Helen Jordan',
        'Jan Arrah',
        'Marcus Aelius',
        'Will Everett',
        'Tex Thompson',
        'Bartholomew Gallows',
        'Tike Alicar',
        'Lonnie Machin',
        'Angelo Bend',
        'Bernhard Baker',
        'Victor Borkowski',
        'Lorena Marquez',
        'Orin Curry',
        'Arthur Curry',
        'Toni Monetti',
        'Jack Keaton',
        'Cissie King-Jones',
        'Roy Harper',
        'Jim Randall',
        'Al Rothstein',
        'Gardner Grayle',
        'Adam Mann',
        'Noah Talbot',
        'William Burns',
        'Jean Paul Valley',
        'Uno Falconer',
        'Curt Falconer',
        'Greg Mattingly',
        'Marc Slayton',
        'Catherine Bell',
        'Suzanne Melotti',
        'Cassandra Lane',
        'Sean Cassidy',
        'Barbara Gordon',
        'Helena Bertinelli',
        'Cassandra Cain',
        'Barbara Wilson',
        'Bruce Wayne',
        'Jackson King',
        'Kathy Kane',
        'Garfield Logan',
        'Barda Free',
        'Percy Pilbeam',
        'Theo Adam',
        'Dinah Lance',
        'Ryan Kendall',
        'Jefferson Pierce',
        'Roman Sionis',
        'Willie Walker',
        'Johnny LaMonica',
        'Natalia Romanova',
        'Janos Prohaska',
        'Dan Garrett',
        'Ted Kord',
        'Jaime Reyes',
        'Daniel Cassidy',
        'Jay Abrams',
        'Michael Carter',
        'Henry King',
        'Ben Turner',
        'Guillermo Barrera',
        'Susan Kent-Barr',
        'Jim Barr',
        'Karen Beecher-Duncan',
        'Ryuko Orsono',
        'Steven Rogers',
        'Allen Adam',
        'Brian Braddock',
        'Daniel Eaton',
        'Tom Evans',
        'David Semple',
        'Darren Oak',
        'Adam Blake',
        'Flash Dale',
        'Tom Townsend',
        'Keith Spencer',
        'Don Wright',
        'Billy Batson',
        'Freddy Freeman',
        'Lance Gallant',
        'Carrie Kelley',
        'Selina Kyle',
        'Drury Walker',
        'Niles Caulder',
        'Katherine Manser',
        'Basil Karlo',
        'Matt Hagen',
        'Preston Payne',
        'Sondra Fuller',
        'Henry Heywood',
        'James Sharp',
        'Jack Ryder',
        'Lee Travis',
        'Albert Elwood',
        'Vivian D’Aramis',
        'Constance D’Aramis',
        'Victor Stone',
        'Scott Summers',
        'Grant Emerson',
        'Matthew Murdock',
        'Boston Brand',
        'Floyd Lawton',
        'Slade Wilson',
        'Victor Von Doom',
        'Anthony Druid',
        'Kent Nelson',
        'Inza Nelson',
        'Hector Hall',
        'Kimiyo Hoshi',
        'Jonathan Osterman',
        'Beth Chapel',
        'Charles McNider',
        'Pieter Cross',
        'Richard Occult',
        'Martha Roberts',
        'Darrell Dane',
        'DeMarr Davis',
        'Don Hall',
        'Dawn Granger',
        'Wiley Wolverman',
        'Daniel Dunbar',
        'Rita Farr',
        'Ralph Dibny',
        'Emma Grace Frost',
        'Anita Fite',
        'David Connor',
        'Jason Blood',
        'Jared Stevens',
        'Beatriz DaCosta',
        'Rod Reilly',
        'Dannette Reilly',
        'Alex Sanchez',
        'Lorraine Reilly',
        'Ronald Raymond ',
        'Martin Stein',
        'Jason Rusch',
        'Bette Kane',
        'Helena Kosmatos',
        'Lyta Hall',
        'Remy LeBeau',
        'Joseph Jones',
        'Brion Markov',
        'Major Dan Stone',
        'Mike Magnus',
        'Charley Parker',
        'Grace Choi',
        'Oliver Queen',
        'Connor Hawke',
        'Phillip Urich',
        'Alan Scott',
        'Hal Jordan',
        'Kyle Rayner',
        'John Stewart',
        'Guy Gardner',
        'Jim Harper',
        'Cindy Reynolds',
        'Shiera Saunders',
        'Kendra Sanders',
        'Carter Hall',
        'Tora Olafsdotter',
        'Robert Drake',
        'Bart Allen',
        'Robert Bruce Banner',
        'Kyle Rayner',
        'Obadiah Stane',
        'Andrea Thomas',
        'Jenny-Lynn Hayden',
        'Jubilation Lee',
        'Cain Marko',
        'Wally West',
        'Bart Allen',
        'Katherine Pryde',
        'Alexander Luthor',
        'Loki Laufeyson',
        'Maz Eisenhardt',
        'Robert Langstrom',
        'J’onn J’onzz',
        'Mary Batson',
        'Rex Mason',
        'Barbara Barton',
        'Reed Richards',
        'Scott Free',
        'Terry Sloane',
        'Michael Holt',
        'James Madrox',
        'Raven Darkholme',
        'Namor McKenzie',
        'Jane St.Ives',
        'Kurt Wagner',
        'Hannibal Hawkes',
        'Richard Grayson',
        'Jason Todd',
        'Barbara Gordon',
        'Adrian Veidt',
        'Hal Jordan',
        'Virginia Potts',
        'William Reilly',
        'Patrick O’Brian',
        'Kara Zor-L',
        'Karen Starr',
        'Charles Xavier',
        'Chen Lu',
        'Edward Nygma',
        'Walter Kovacs',
        'Dick Grayson',
        'Jason Todd',
        'Tim Drake',
        'Stephanie Brown',
        'Anna Marie',
        'Walter Langkowski',
        'Molly Fitzgerald',
        'Steven Harmon',
        'Cyrus Gold',
        'Melissa Gold',
        'Hal Jordan',
        'Crispus Allen',
        'Robert Baldwin',
        'Peter Parker',
        'William Nasland',
        'Courtney Whitmore',
        'Ted Knight',
        'David Knight',
        'Jack Knight',
        'Henry Heywood',
        'Natasha Irons',
        'Ororo Monroe',
        'Shiro Yoshida',
        'Kara Zor-El',
        'Linda Danvers',
        'Ariella Kent',
        'Clark Kent',
        'Alec Holland',
        'Joshua Sanders',
        'Albert Pratt',
        'Ramond Palmer',
        'Adam Cray',
        'Richard Benson',
        'Paul Cannon',
        'Norbert Sykes',
        'Barry Eames',
        'Jay Garrick',
        'Barry Allen',
        'Wallace West',
        'Jack Napier',
        'Oswald Cobblepot',
        'Charles Szasz',
        'Vic Sage',
        'Benjamin Grimm',
        'Donna Troy',
        'Harvey Dent',
        'Troy Stewart',
        'Edith Sawyer',
        'Samantha Parrington',
        'Guy Gardner',
        'William Kaplan',
        'James Howlett',
        'Donna Troy',
        'Cassie Sandsmark',
        'Princess Diana',
        'Queen Hippolyta'
    );
    private $people_count;
    private $goals = array( 0, 500, 1000, 2500, 10000, 50000, 100000, 200000, 500000, 1000000 );
    private $goal_count;
    private $end_date_offsets = array(
        0, 
        '+1 day', 
        'now', 
        '-15 days', 
        '+15 days', 
        '+150 days', 
        '-150 days'
    );
    private $end_date_count;
    private $suggested_donations = array(
        array(), 
        array( 
            array( 'amount' => 10, 'description' => '' ), 
            array( 'amount' => 25, 'description' => '' ), 
            array( 'amount' => 50, 'description' => '' ) 
        ), 
        array(
            array( 'amount' => 14, 'description' => 'Bronze' ), 
            array( 'amount' => 38, 'description' => 'Silver' ), 
            array( 'amount' => 79, 'description' => 'Gold' ), 
            array( 'amount' => 113, 'description' => 'Platinum' ),
            array( 'amount' => 198, 'description' => 'Adamantium' )
        )
    );    
    private $suggested_donations_count;
    private $amounts = array(
        2, 4, 5, 10, 15, 25, 40, 50, 75, 100, 120, 150, 200, 250, 500, 800, 1200
    );
    private $amounts_count;
    private $users = array();
    private $campaigns = array();
    private $donations = array();

    public function __construct( $args ) {
        if ( isset( $args[ 0 ] ) ) {
            $this->donations_count = $args[ 0 ];
        }

        if ( isset( $args[ 1 ] ) ) {
            $this->campaigns_count = $args[ 1 ];
        }
        
        $this->people_count = count( $this->people );

        $this->goal_count = count( $this->goals );

        $this->end_date_count = count( $this->end_date_offsets );

        $this->suggested_donations_count = count( $this->suggested_donations );

        $this->amounts_count = count( $this->amounts );

        $this->make_campaigns();

        $this->make_donations();
    }

    public function log( $message ) {
        echo $message . PHP_EOL;
    }

    public function make_campaigns() {
        $campaigns = wp_count_posts( 'campaign' );
        $published = $campaigns->publish;

        for ( $i = 1; $i <= $this->campaigns_count; $i++ ) {
            $this->campaigns[] = $this->make_campaign( $i + $published );
        }
    }

    public function make_donations() {
        $donations = wp_count_posts( 'donation' );
        $published = $donations->publish;

        for ( $i = 1; $i <= $this->donations_count; $i++ ) {
            $this->donations[] = $this->make_donation( $i + $published );
        }
    }

    public function make_campaign( $i ) {
        $user = $this->make_user();

        $title = sprintf( 'Campaign %d', $i );

        $campaign_id = wp_insert_post( array(
            'post_title'    => $title,
            'post_type'     => 'campaign', 
            'post_status'   => 'publish', 
            'post_author'   => $user[ 'ID' ],
            'post_content'  => sprintf( 'Content of campaign %d', $i )
        ) );

        $meta = array( 
            '_campaign_description' => sprintf( 'Description of campaign %d', $i ),
            '_campaign_goal' => $this->get_random_goal(), 
            '_campaign_end_date' => $this->get_random_end_date(), 
            '_campaign_suggested_donations' => $this->get_random_suggested_donations(), 
            '_campaign_allow_custom_donations' => 1
        );

        foreach ( $meta as $key => $value ) {
            add_post_meta( $campaign_id, $key, $value );
        }

        $this->log( sprintf( 'New campaign: %s (%d)', $title, $campaign_id ) );

        return array( 'ID' => $campaign_id, 'title' => $title );
    }

    public function make_donation( $i ) {
        $user = $this->make_user();
        $campaign = $this->get_random_campaign();
        $amount = $this->get_random_amount();

        $args = array(
            'user' => array(
                'email' => $user[ 'user_email' ],
                'first_name' => $user[ 'first_name' ],
                'last_name' => $user[ 'last_name' ], 
                'address' => '',
                'address_2' => '',
                'city' => '',
                'state' => '',
                'postcode' => '',
                'country' => ''
            ),
            'campaigns'     => array(
                array(
                    'campaign_id'   => $campaign[ 'ID' ],
                    'campaign_name' => $campaign[ 'title' ], 
                    'amount'        => $amount
                )                
            ), 
            'status'        => 'charitable-completed', 
            'gateway'       => 'manual'
        );

        $this->log( sprintf( '$%s donation to campaign %s', $amount, $campaign[ 'title' ] ) );

        $donation_id = Charitable_Donation_Processor::get_instance()->save_donation( $args );

        Charitable_Donation_Processor::destroy();

        return $donation_id;
    }

    public function make_user() {
        $idx = rand( 0, $this->people_count - 1);
        $name = $this->people[ $idx ];

        if ( isset( $this->users[ $name ] ) ) {
            return $this->users[ $name ];
        }

        list( $first_name, $last_name ) = explode( ' ', $name, 2 );

        $user = array(
            'display_name' => $name,
            'first_name' => $first_name, 
            'last_name' => $last_name, 
            'user_email' => str_replace( ' ', '', sprintf( '%s.%s@example.com', $first_name, $last_name ) ), 
            'user_pass' => 'password',
            'user_login' => str_replace( '-', '', strtolower( sprintf( '%s-%s', $first_name, $last_name ) ) )
        );

        $id = wp_insert_user( $user );

        $user[ 'ID' ] = $id;

        $this->users[ $name ] = $user;

        $this->log( sprintf( 'New user: %s', $name ) );

        return $user;
    }

    public function get_random_goal() {
        $idx = rand( 0, $this->goal_count - 1 );
        return $this->goals[ $idx ];
    }

    public function get_random_end_date() {
        $idx = rand( 0, $this->end_date_count - 1 );
        $end_date_offset = $this->end_date_offsets[ $idx ];

        if ( $end_date_offset == 0 ) {
            return 0;
        }

        return date( 'Y-m-d H:i:s', strtotime( $end_date_offset ) );
    }

    public function get_random_suggested_donations() {
        $idx = rand( 0, $this->suggested_donations_count - 1 );
        return $this->suggested_donations[ $idx ];
    }

    public function get_random_campaign() {
        $idx = rand( 0, $this->campaigns_count - 1 );
        return $this->campaigns[ $idx ];
    }

    public function get_random_amount() {
        $idx = rand( 0, $this->amounts_count - 1 );
        return $this->amounts[ $idx ];
    }
}

// Do your magic
new Charitable_Data_Spawn( $args );