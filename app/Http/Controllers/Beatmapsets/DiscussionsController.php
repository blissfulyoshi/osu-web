<?php

/**
 *    Copyright 2015 ppy Pty. Ltd.
 *
 *    This file is part of osu!web. osu!web is distributed with the hope of
 *    attracting more community contributions to the core ecosystem of osu!.
 *
 *    osu!web is free software: you can redistribute it and/or modify
 *    it under the terms of the Affero GNU General Public License version 3
 *    as published by the Free Software Foundation.
 *
 *    osu!web is distributed WITHOUT ANY WARRANTY; without even the implied
 *    warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *    See the GNU Affero General Public License for more details.
 *
 *    You should have received a copy of the GNU Affero General Public License
 *    along with osu!web.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace App\Http\Controllers\Beatmapsets;

use App\Models\BeatmapSet;
use App\Transformers\BeatmapSetTransformer;
use Auth;

class DiscussionsController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        view()->share('current_action', 'beatmapsets-discussions-'.current_action());
    }

    public function show($beatmapsetId)
    {
        $user = Auth::user();

        $beatmapset = BeatmapSet::findOrFail($beatmapsetId);
        $discussion = $beatmapset->beatmapsetDiscussion;

        $userPermissions = [
            'can_post_new' => $discussion->canBePostedBy(Auth::user()),
            'beatmap_discussions' => [],
        ];

        $initialData = [
            'beatmapset' => fractal_item_array(
                $beatmapset,
                new BeatmapSetTransformer,
                'user,beatmaps'
            ),

            'beatmapsetDiscussion' => $discussion->defaultJson(),
        ];

        return view('beatmapsets.discussions.show', compact('initialData'));
    }
}
