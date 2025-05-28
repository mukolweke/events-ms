-- Migration to update users table and create users_organizations pivot table

-- Step 1: Create the new pivot table
CREATE TABLE users_organizations (
    user_id INT NOT NULL,
    organization_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, organization_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (organization_id) REFERENCES organizations(id) ON DELETE CASCADE
);

-- Step 2: Migrate existing organization_id data to the pivot table
INSERT INTO users_organizations (user_id, organization_id)
SELECT id, organization_id FROM users WHERE organization_id IS NOT NULL;

-- Step 3: Remove the organization_id column from the users table
ALTER TABLE users DROP COLUMN organization_id;
