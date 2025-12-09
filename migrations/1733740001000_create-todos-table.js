/* eslint-disable camelcase */

exports.shorthands = undefined;

exports.up = (pgm) => {
  pgm.createTable('todos', {
    id: {
      type: 'serial',
      primaryKey: true,
      notNull: true,
    },
    user_id: {
      type: 'integer',
      notNull: true,
      references: 'users',
      onDelete: 'CASCADE',
    },
    title: {
      type: 'varchar(255)',
      notNull: true,
    },
    description: {
      type: 'text',
    },
    status: {
      type: 'varchar(50)',
      notNull: true,
      default: "'pending'",
    },
    priority: {
      type: 'varchar(20)',
      default: "'medium'",
    },
    due_date: {
      type: 'timestamp',
    },
    completed_at: {
      type: 'timestamp',
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

  // Create index on user_id for faster lookups
  pgm.createIndex('todos', 'user_id');
  
  // Create index on status for filtering
  pgm.createIndex('todos', 'status');
  
  // Create index on due_date for sorting
  pgm.createIndex('todos', 'due_date');
};

exports.down = (pgm) => {
  pgm.dropTable('todos');
};
