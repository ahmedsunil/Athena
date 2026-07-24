<?php

use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\WebsiteController;
use App\Livewire\AppManagement\SystemSettings;
use App\Livewire\Auditing\ActivityLog;
use App\Livewire\Auditing\ActivityLogShow;
use App\Livewire\Cms\FooterLinks\FooterLinksIndex;
use App\Livewire\Cms\FooterLinks\FooterLinkForm;
use App\Livewire\Cms\Announcements\AnnouncementsIndex;
use App\Livewire\Cms\Announcements\AnnouncementForm;
use App\Livewire\Cms\HomeQuickAccess\HomeQuickAccessIndex;
use App\Livewire\Cms\HomeQuickAccess\HomeQuickAccessForm;
use App\Livewire\Cms\HomeSlides\HomeSlidesIndex;
use App\Livewire\Cms\HomeSlides\HomeSlideForm;
use App\Livewire\Cms\HomeStats\HomeStatsIndex;
use App\Livewire\Cms\HomeStats\HomeStatForm;
use App\Livewire\Cms\HomeTestimonials\HomeTestimonialsIndex;
use App\Livewire\Cms\HomeTestimonials\HomeTestimonialForm;
use App\Livewire\Cms\Icons\IconsIndex;
use App\Livewire\Cms\Links\LinksIndex;
use App\Livewire\Cms\SchoolProfile\SchoolProfileEdit;
use App\Livewire\Dashboard;
use App\Livewire\Profile\ProfileSettings;
use App\Livewire\Roles\RoleForm;
use App\Livewire\Roles\RoleShow;
use App\Livewire\Roles\RolesList;
use App\Livewire\Users\UserForm;
use App\Livewire\Users\UserShow;
use App\Livewire\Users\UsersList;
use App\Livewire\Website\Home;
use App\Livewire\Website\Announcements;
use App\Livewire\Website\AnnouncementShow;
use App\Livewire\Cms\Events\EventsIndex;
use App\Livewire\Cms\Events\EventForm;
use App\Livewire\Website\Events;
use App\Livewire\Website\EventShow;
use App\Livewire\Cms\About\MissionEdit;
use App\Livewire\Cms\About\LeadershipIndex;
use App\Livewire\Cms\About\LeadershipForm;
use App\Livewire\Cms\About\FoundingMembersIndex;
use App\Livewire\Cms\About\FoundingMemberForm;
use App\Livewire\Cms\About\HistorySectionForm;
use App\Livewire\Cms\About\HistorySectionsIndex;
use App\Livewire\Cms\About\AchievementsIndex;
use App\Livewire\Cms\About\AchievementForm;
use App\Livewire\Cms\About\StaffIndex;
use App\Livewire\Cms\About\StaffForm;
use App\Livewire\Website\About;
use App\Livewire\Cms\Academics\AcademicsOverviewEdit;
use App\Livewire\Cms\Academics\AcademicLevelsIndex;
use App\Livewire\Cms\Academics\AcademicLevelForm;
use App\Livewire\Website\Academics;
use App\Livewire\Cms\StudentLife\ClubForm;
use App\Livewire\Cms\StudentLife\ClubsIndex;
use App\Livewire\Cms\StudentLife\HouseForm;
use App\Livewire\Cms\StudentLife\HousesIndex;
use App\Livewire\Cms\StudentLife\PeopleIndex;
use App\Livewire\Cms\StudentLife\PersonForm;
use App\Livewire\Cms\StudentLife\PrefectForm;
use App\Livewire\Cms\StudentLife\PrefectsIndex;
use App\Livewire\Cms\StudentLife\UniformBodiesIndex;
use App\Livewire\Cms\StudentLife\UniformBodyForm;
use App\Livewire\Website\StudentLife;
use App\Livewire\Website\StudentLifeShow;
use App\Livewire\Cms\Gallery\GalleryIndex;
use App\Livewire\Cms\Gallery\GalleryForm;
use App\Livewire\Cms\DigitalServices\DocumentsIndex;
use App\Livewire\Cms\DigitalServices\DocumentForm;
use App\Livewire\Cms\DigitalServices\ResourcesIndex;
use App\Livewire\Cms\DigitalServices\ResourceForm;
use App\Livewire\Cms\DigitalServices\CalendarsIndex;
use App\Livewire\Cms\DigitalServices\CalendarForm;
use App\Livewire\Cms\DigitalServices\CalendarEntriesIndex;
use App\Livewire\Cms\Apps\WebAppsIndex;
use App\Livewire\Cms\Apps\WebAppForm;
use App\Livewire\Website\DigitalServices;
use App\Livewire\Website\Apps;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', ProfileSettings::class)->name('profile.index');

    Route::middleware('role:admin')->group(function () {
        Route::get('/dashboard', Dashboard::class)->name('dashboard');

        // User management
        Route::get('/users', UsersList::class)->name('users.index');
        Route::get('/users/create', UserForm::class)->name('users.create');
        Route::get('/users/{userId}', UserShow::class)->whereNumber('userId')->name('users.show');
        Route::get('/users/{userId}/edit', UserForm::class)->name('users.edit');
        // Roles management
        Route::get('/roles', RolesList::class)->name('roles.index');
        Route::get('/roles/create', RoleForm::class)->name('roles.create');
        Route::get('/roles/{roleId}', RoleShow::class)->whereNumber('roleId')->name('roles.show');
        Route::get('/roles/{roleId}/edit', RoleForm::class)->name('roles.edit');

        // App Management
        Route::get('/app/activity', ActivityLog::class)->name('app.activity');
        Route::get('/app/activity/{activityId}', ActivityLogShow::class)
            ->whereNumber('activityId')
            ->name('app.activity.show');
        Route::get('/app/settings', SystemSettings::class)->name('app.settings');

        // CMS — config registries
        Route::get('/cms/links', LinksIndex::class)->name('cms.links.index');
        Route::get('/cms/icons', IconsIndex::class)->name('cms.icons.index');

        // CMS — content modules
        Route::get('/cms/school-profile', SchoolProfileEdit::class)->name('cms.school-profile');
        Route::get('/cms/footer-links', FooterLinksIndex::class)->name('cms.footer-links');
        Route::get('/cms/footer-links/create', FooterLinkForm::class)->name('cms.footer-links.create');
        Route::get('/cms/footer-links/{linkId}/edit', FooterLinkForm::class)->whereNumber('linkId')->name('cms.footer-links.edit');
        // CMS — apps module
        Route::get('/cms/apps', WebAppsIndex::class)->name('cms.apps');
        Route::get('/cms/apps/create', WebAppForm::class)->name('cms.apps.create');
        Route::get('/cms/apps/{appId}/edit', WebAppForm::class)->whereNumber('appId')->name('cms.apps.edit');

        // CMS — content modules
        Route::get('/cms/school-profile', SchoolProfileEdit::class)->name('cms.school-profile');
        Route::get('/cms/home/slides/create', HomeSlideForm::class)->name('cms.home.slides.create');
        Route::get('/cms/home/slides/{itemId}/edit', HomeSlideForm::class)->whereNumber('itemId')->name('cms.home.slides.edit');
        Route::get('/cms/home/stats', HomeStatsIndex::class)->name('cms.home.stats');
        Route::get('/cms/home/stats/create', HomeStatForm::class)->name('cms.home.stats.create');
        Route::get('/cms/home/stats/{itemId}/edit', HomeStatForm::class)->whereNumber('itemId')->name('cms.home.stats.edit');
        Route::get('/cms/home/quick-access', HomeQuickAccessIndex::class)->name('cms.home.quick-access');
        Route::get('/cms/home/quick-access/create', HomeQuickAccessForm::class)->name('cms.home.quick-access.create');
        Route::get('/cms/home/quick-access/{itemId}/edit', HomeQuickAccessForm::class)->whereNumber('itemId')->name('cms.home.quick-access.edit');
        Route::get('/cms/home/testimonials', HomeTestimonialsIndex::class)->name('cms.home.testimonials');
        Route::get('/cms/home/testimonials/create', HomeTestimonialForm::class)->name('cms.home.testimonials.create');
        Route::get('/cms/home/testimonials/{itemId}/edit', HomeTestimonialForm::class)->whereNumber('itemId')->name('cms.home.testimonials.edit');
        Route::get('/cms/announcements', AnnouncementsIndex::class)->name('cms.announcements.index');
        Route::get('/cms/announcements/create', AnnouncementForm::class)->name('cms.announcements.create');
        Route::get('/cms/announcements/{announcementId}/edit', AnnouncementForm::class)->whereNumber('announcementId')->name('cms.announcements.edit');

        // CMS — about module
        Route::get('/cms/about/mission', MissionEdit::class)->name('cms.about.mission');
        Route::get('/cms/about/leadership', LeadershipIndex::class)->name('cms.about.leadership');
        Route::get('/cms/about/leadership/create', LeadershipForm::class)->name('cms.about.leadership.create');
        Route::get('/cms/about/leadership/{leadershipId}/edit', LeadershipForm::class)->whereNumber('leadershipId')->name('cms.about.leadership.edit');
        Route::get('/cms/about/founding-members', FoundingMembersIndex::class)->name('cms.about.founding-members');
        Route::get('/cms/about/founding-members/create', FoundingMemberForm::class)->name('cms.about.founding-members.create');
        Route::get('/cms/about/founding-members/{foundingId}/edit', FoundingMemberForm::class)->whereNumber('foundingId')->name('cms.about.founding-members.edit');
        Route::get('/cms/history', HistorySectionsIndex::class)->name('cms.history');
        Route::get('/cms/history/create', HistorySectionForm::class)->name('cms.history.create');
        Route::get('/cms/history/{sectionId}/edit', HistorySectionForm::class)->whereNumber('sectionId')->name('cms.history.edit');
        Route::get('/cms/achievements', AchievementsIndex::class)->name('cms.achievements');
        Route::get('/cms/achievements/create', AchievementForm::class)->name('cms.achievements.create');
        Route::get('/cms/achievements/{achievementId}/edit', AchievementForm::class)->whereNumber('achievementId')->name('cms.achievements.edit');
        Route::get('/cms/team', StaffIndex::class)->name('cms.team');
        Route::get('/cms/team/create', StaffForm::class)->name('cms.team.create');
        Route::get('/cms/team/{staffId}/edit', StaffForm::class)->whereNumber('staffId')->name('cms.team.edit');

        // CMS — events module
        Route::get('/cms/events', EventsIndex::class)->name('cms.events.index');
        Route::get('/cms/events/create', EventForm::class)->name('cms.events.create');
        Route::get('/cms/events/{eventId}/edit', EventForm::class)->whereNumber('eventId')->name('cms.events.edit');

        // CMS — academics module
        Route::get('/cms/academics/overview', AcademicsOverviewEdit::class)->name('cms.academics.overview');
        Route::get('/cms/academics/levels', AcademicLevelsIndex::class)->name('cms.academics.levels');
        Route::get('/cms/academics/levels/create', AcademicLevelForm::class)->name('cms.academics.levels.create');
        Route::get('/cms/academics/levels/{levelId}/edit', AcademicLevelForm::class)->whereNumber('levelId')->name('cms.academics.levels.edit');

        // CMS — student life module
        Route::get('/cms/clubs',                    ClubsIndex::class)->name('cms.clubs');
        Route::get('/cms/clubs/create',             ClubForm::class)->name('cms.clubs.create');
        Route::get('/cms/clubs/{club}/edit',        ClubForm::class)->whereNumber('club')->name('cms.clubs.edit');
        Route::get('/cms/prefects',                 PrefectsIndex::class)->name('cms.prefects');
        Route::get('/cms/prefects/create',          PrefectForm::class)->name('cms.prefects.create');
        Route::get('/cms/prefects/{prefect}/edit',  PrefectForm::class)->whereNumber('prefect')->name('cms.prefects.edit');
        Route::get('/cms/houses',                   HousesIndex::class)->name('cms.houses');
        Route::get('/cms/houses/create',            HouseForm::class)->name('cms.houses.create');
        Route::get('/cms/houses/{house}/edit',      HouseForm::class)->whereNumber('house')->name('cms.houses.edit');
        Route::get('/cms/uniform-bodies',           UniformBodiesIndex::class)->name('cms.uniform-bodies');
        Route::get('/cms/uniform-bodies/create',    UniformBodyForm::class)->name('cms.uniform-bodies.create');
        Route::get('/cms/uniform-bodies/{body}/edit', UniformBodyForm::class)->whereNumber('body')->name('cms.uniform-bodies.edit');
        Route::get('/cms/people',                   PeopleIndex::class)->name('cms.people');
        Route::get('/cms/people/create',            PersonForm::class)->name('cms.people.create');
        Route::get('/cms/people/{person}/edit',     PersonForm::class)->whereNumber('person')->name('cms.people.edit');

        // CMS — gallery module
        Route::get('/cms/gallery', GalleryIndex::class)->name('cms.gallery.index');
        Route::get('/cms/gallery/create', GalleryForm::class)->name('cms.gallery.create');
        Route::get('/cms/gallery/{albumId}/edit', GalleryForm::class)->whereNumber('albumId')->name('cms.gallery.edit');

        // CMS — digital services module
        Route::get('/cms/digital-services/documents',                  DocumentsIndex::class)->name('cms.digital-services.documents');
        Route::get('/cms/digital-services/documents/create',           DocumentForm::class)->name('cms.digital-services.documents.create');
        Route::get('/cms/digital-services/documents/{itemId}/edit',    DocumentForm::class)->whereNumber('itemId')->name('cms.digital-services.documents.edit');
        Route::get('/cms/digital-services/resources',                  ResourcesIndex::class)->name('cms.digital-services.resources');
        Route::get('/cms/digital-services/resources/create',           ResourceForm::class)->name('cms.digital-services.resources.create');
        Route::get('/cms/digital-services/resources/{itemId}/edit',    ResourceForm::class)->whereNumber('itemId')->name('cms.digital-services.resources.edit');
        Route::get('/cms/digital-services/calendars',                  CalendarsIndex::class)->name('cms.digital-services.calendars');
        Route::get('/cms/digital-services/calendars/create',           CalendarForm::class)->name('cms.digital-services.calendars.create');
        Route::get('/cms/digital-services/calendars/{itemId}/edit',    CalendarForm::class)->whereNumber('itemId')->name('cms.digital-services.calendars.edit');
        Route::get('/cms/digital-services/calendar',                   CalendarEntriesIndex::class)->name('cms.digital-services.calendar');
    });
});

// Google OAuth
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

// Public website
Route::get('/', [WebsiteController::class, 'home'])->name('home');
Route::get('/site-search', [WebsiteController::class, 'search'])->name('website.search');
Route::get('/announcements', [WebsiteController::class, 'announcements'])->name('announcements.index');
Route::get('/announcements/{slug}', [WebsiteController::class, 'announcementShow'])->name('announcements.show');
Route::get('/about', [WebsiteController::class, 'about'])->name('about');
Route::get('/events', [WebsiteController::class, 'events'])->name('events.index');
Route::get('/events/{slug}', [WebsiteController::class, 'eventShow'])->name('events.show');
Route::get('/academics', [WebsiteController::class, 'academics'])->name('academics.index');
Route::get('/student-life', [WebsiteController::class, 'studentLife'])->name('student-life.index');
Route::get('/student-life/{type}/{id}', [WebsiteController::class, 'studentLifeShow'])
    ->whereIn('type', ['clubs', 'houses', 'prefects', 'uniform-bodies'])
    ->whereNumber('id')
    ->name('student-life.show');
Route::get('/digital-services', [WebsiteController::class, 'digitalServices'])->name('digital-services.index');
Route::get('/apps', [WebsiteController::class, 'apps'])->name('apps.index');
Route::get('/admissions', fn () => 'Admissions')->name('admissions');
