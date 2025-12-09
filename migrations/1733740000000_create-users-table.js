/* eslint-disable camelcase */

exports.shorthands = undefined;

exports.up = (pgm) => {
  pgm.createTable('users', {
    id: {
      type: 'serial',
      primaryKey: true,
      notNull: true,
    },
    email: {
      type: 'varchar(255)',
      notNull: true,
      unique: true,
    },
    username: {
      type: 'varchar(100)',
      notNull: true,
      unique: true,
    },
    password_hash: {
      type: 'varchar(255)',
      notNull: true,
    },
    first_name: {
      type: 'varchar(100)',
    },
    last_name: {
      type: 'varchar(100)',
    },
    created_at: {
      type: 'timestamp',
      notNull: true,
      default: pgm.func('current_timestamp'),
    },
    updated_at: {
      type: 'timestamp',
      notNull: true,
      default: pgm.func('current_timestamp'),
    },
  });

  // Create index on email for faster lookups
  pgm.createIndex('users', 'email');
  
  // Create index on username for faster lookups
  pgm.createIndex('users', 'username');
};

exports.down = (pgm) => {
  pgm.dropTable('users');
};
