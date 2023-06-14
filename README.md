# Game of Knowledge

Another gamification plugin.

Game of Knowledge is a Moodle activity plugin providing a simple quiz game, but using the framework vue.js to spice up the user experience.

### Origin & Destination

The prototype for this activity was developed during the DevCamp at MoodleMoot DACH 2023 in Zürich.  
The goal is to provide vue.js integration as a reactive frontend for Moodle plugins.  
This plugin can be used as a basis for future plugin development.

## Moodle Plugin Installation

The following sections outline how to install the Moodle plugin.

### Command Line Installation

To install the plugin in Moodle via the command line: (assumes a Linux based system)

1. Get the code from GitHub or the Moodle Plugin Directory.
2. Copy or clone code into: `<moodledir>/mod `and name it `gameofknowledge`
3. Run the upgrade: `sudo -u user php admin/cli/upgrade` **Note:** the user may be different to user on your system.

### User Interface Installation

To install the plugin in Moodle via the Moodle User Interface:

1. Log into your Moodle as an Administrator.
2. Navigate to: `Site administration > Plugins > Install Plugins`
3. Install plugin from Moodle Plugin directory or via zip upload.

## Usage

The activity can be inserted in any course via the activity chooser:

- Give it a name
- Choose your questions from the question bank
- Set a layout (S = Start; Q = Question; # = Movable field; \_ = Placeholder/Empty)

That's it!

After you have opened the activity, you can start playing directly by clicking on "Start game".

![image (2).png](.attachments.410187/image%20%282%29.png)


## Misc stuff

### (Planned) Features

- Implementation of Moodle Question API
- Reactive frontend based on vue.js
- Singleplayer & Multiplayer
- Several game modes like "Same for all", "Choose your topic" or "Random"

### Contributor

- Tim Trappen
- Lars Dreier
- Lars Bonczek
- Adrian C...
- Martin Gauk
- Jan Eberhardt
- Stefan Bomanns

## License

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program.  If not, see <http://www.gnu.org/licenses/>.

### Github Repository

<https://github.com/MoodleNRW/moodle-mod_gameofknowledge>
