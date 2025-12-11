<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\Subtopic;
use App\Models\Content;
use App\Models\Resource;
use App\Models\LearningOutcome;

class SriLankaCurriculumSeeder extends Seeder
{
    public function run(): void
    {
        // Reduce these for local testing; set to 10/10 only when you are ready
        $maxTopics = env('SEED_MAX_TOPICS', 10);
        $maxSubtopics = env('SEED_MAX_SUBTOPICS', 10);

        // Disable query log to save memory
        DB::disableQueryLog();
        Model::unguard();

        // curriculum definitions (same as before)...
        $primarySubjects = [
            ['code' => 'ML', 'name' => 'Mother Language'],
            ['code' => 'MATH', 'name' => 'Mathematics'],
            ['code' => 'EVS', 'name' => 'Environmental Studies'],
            ['code' => 'REL', 'name' => 'Religion'],
            ['code' => 'ENG', 'name' => 'English'],
            ['code' => 'AEST', 'name' => 'Aesthetic Education'],
        ];
        $juniorSubjects = [
            ['code' => 'ML', 'name' => 'Mother Language'],
            ['code' => 'ENG', 'name' => 'English'],
            ['code' => 'MATH', 'name' => 'Mathematics'],
            ['code' => 'SCI', 'name' => 'Science'],
            ['code' => 'HIS', 'name' => 'History'],
            ['code' => 'GEO', 'name' => 'Geography'],
            ['code' => 'REL', 'name' => 'Religion'],
            ['code' => 'PE',  'name' => 'Health & Physical Education'],
            ['code' => 'AEST','name' => 'Aesthetic Subjects'],
        ];
        $olCompulsory = [
            ['code' => 'ML', 'name' => 'Mother Language'],
            ['code' => 'ENG', 'name' => 'English'],
            ['code' => 'MATH', 'name' => 'Mathematics'],
            ['code' => 'SCI', 'name' => 'Science'],
            ['code' => 'HIS', 'name' => 'History'],
            ['code' => 'REL', 'name' => 'Religion'],
        ];
        $olOptionals = [
            ['code' => 'ICT', 'name' => 'ICT'],
            ['code' => 'ACCT','name' => 'Business & Accounting Studies'],
            ['code' => 'AGRI','name' => 'Agriculture & Food Technology'],
            ['code' => 'AEST','name' => 'Aesthetic (Art/Music)'],
            ['code' => 'PE','name' => 'Health & Physical Education'],
            ['code' => 'GEO','name' => 'Geography'],
            ['code' => 'CIV','name' => 'Civic Education'],
            ['code' => 'ENT','name' => 'Entrepreneurship Studies'],
            ['code' => 'TASL','name' => 'Tamil/Sinhala as Second Language'],
            ['code' => 'ECO','name' => 'Economics'],
        ];
        $alStreams = [
            'Science' => [
                ['code' => 'PHY','name'=>'Physics'],
                ['code' => 'CHM','name'=>'Chemistry'],
                ['code' => 'BIO','name'=>'Biology'],
                ['code' => 'CMATH','name'=>'Combined Mathematics'],
            ],
            'Commerce' => [
                ['code' => 'ACCT','name'=>'Accounting'],
                ['code' => 'BUS','name'=>'Business Studies'],
                ['code' => 'ECO','name'=>'Economics'],
            ],
            'Arts' => [
                ['code' => 'SIN','name'=>'Sinhala/Tamil'],
                ['code' => 'ENG','name'=>'English'],
                ['code' => 'HUM','name'=>'Humanities'],
                ['code' => 'POL','name'=>'Political Science'],
                ['code' => 'HIST','name'=>'History'],
            ],
            'Technology' => [
                ['code' => 'ICT','name'=>'ICT'],
                ['code' => 'ENGTECH','name'=>'Engineering Technology'],
                ['code' => 'SCITECH','name'=>'Science for Technology'],
            ],
        ];
        $alCommon = [
            ['code'=>'GENENG','name'=>'General English'],
            ['code'=>'CGT','name'=>'Common General Test'],
        ];

        // helper that creates topics/subtopics for one subject inside its own transaction
        $makeTopicsAndSubtopics = function (Subject $subject) use ($maxTopics, $maxSubtopics) {
            DB::transaction(function () use ($subject, $maxTopics, $maxSubtopics) {
                for ($t = 1; $t <= $maxTopics; $t++) {
                    $topicCode = $subject->subject_code . '-T' . $t;
                    $topicName = $subject->subject_name . " - Topic {$t}";
                    $topic = Topic::updateOrCreate(
                        ['topic_code' => $topicCode],
                        [
                            'subject_id' => $subject->id,
                            'topic_name' => $topicName,
                            'description' => "Overview of {$topicName}",
                        ]
                    );

                    for ($s = 1; $s <= $maxSubtopics; $s++) {
                        $subCode = $topic->topic_code . '-S' . $s;
                        $subName = $topicName . " — Subtopic {$s}";

                        $sub = Subtopic::updateOrCreate(
                            ['subtopic_code' => $subCode],
                            [
                                'topic_id' => $topic->id,
                                'subtopic_name' => $subName,
                                'description' => "Details for {$subName}",
                            ]
                        );

                        Content::updateOrCreate(
                            ['subtopic_id' => $sub->id, 'title' => $subName . ' — Lesson'],
                            [
                                'type' => 'text',
                                'content' => "{$subject->subject_name} content for {$subName}",
                            ]
                        );

                        // Create 3 resources: text, video, image
                        // TEXT RESOURCE
                        Resource::updateOrCreate(
                            [
                                'resourceable_type' => Subtopic::class,
                                'resourceable_id' => $sub->id,
                                'type' => 'text',
                            ],
                            [
                                'content' => "{$subject->subject_name} text notes for {$subName}. Read this material carefully to understand the concepts. This is comprehensive study material covering all key points and definitions.",
                            ]
                        );

                        // VIDEO RESOURCE - create dummy video file
                        $videoPath = $this->createDummyVideoFile($subject->subject_code, $t, $s);
                        Resource::updateOrCreate(
                            [
                                'resourceable_type' => Subtopic::class,
                                'resourceable_id' => $sub->id,
                                'type' => 'video',
                            ],
                            [
                                'content' => $videoPath,
                            ]
                        );

                        // IMAGE RESOURCE - create dummy image file
                        $imagePath = $this->createDummyImageFile($subject->subject_code, $t, $s);
                        Resource::updateOrCreate(
                            [
                                'resourceable_type' => Subtopic::class,
                                'resourceable_id' => $sub->id,
                                'type' => 'image',
                            ],
                            [
                                'content' => $imagePath,
                            ]
                        );

                        LearningOutcome::updateOrCreate(
                            ['subtopic_id' => $sub->id, 'outcome' => 'Understand ' . strtolower($subject->subject_name) . ' ' . $t . '.' . $s],
                            ['difficulty_level' => ($s % 3 == 0) ? 'hard' : (($s % 2 == 0) ? 'medium' : 'easy')]
                        );
                    }
                }
            }); // end per-subject transaction
        };

        // small helper to create a subject and immediately add topics (no huge global transaction)
        $createSubjectAndAdd = function (string $subject_code, string $subject_name, string $gradeLabel) use ($makeTopicsAndSubtopics) {
            $subject = Subject::updateOrCreate(
                ['subject_code' => $subject_code],
                [
                    'subject_name' => $subject_name,
                    'grade' => $gradeLabel,
                    'language' => 'Sinhala',
                    'status' => 'active',
                    'description' => $subject_name . ' for ' . $gradeLabel,
                ]
            );

            // info to console
            $this->command->info("Created subject: {$subject_code}");

            // create topics+subtopics in limited-size transaction
            $makeTopicsAndSubtopics($subject);
        };

        // create curriculum — iterate grades and call helper (commits per subject)
        // Grades 1..5
        for ($g = 1; $g <= 5; $g++) {
            $gradeLabel = 'Grade ' . $g;
            foreach ($primarySubjects as $s) {
                $code = strtoupper($s['code']) . '-G' . $g;
                $createSubjectAndAdd($code, $s['name'], $gradeLabel);
            }
        }

        // Grades 6..9
        for ($g = 6; $g <= 9; $g++) {
            $gradeLabel = 'Grade ' . $g;
            foreach ($juniorSubjects as $s) {
                $code = strtoupper($s['code']) . '-G' . $g;
                $createSubjectAndAdd($code, $s['name'], $gradeLabel);
            }
        }

        // Grades 10..11 - compulsory + optionals (all)
        for ($g = 10; $g <= 11; $g++) {
            $gradeLabel = 'Grade ' . $g;
            foreach ($olCompulsory as $s) {
                $createSubjectAndAdd(strtoupper($s['code']) . '-G' . $g, $s['name'], $gradeLabel);
            }
            foreach ($olOptionals as $s) {
                $createSubjectAndAdd(strtoupper($s['code']) . '-G' . $g, $s['name'], $gradeLabel);
            }
        }

        // Grades 12..13 - streams + common + extras
        for ($g = 12; $g <= 13; $g++) {
            $gradeLabel = 'Grade ' . $g;
            foreach ($alStreams as $streamName => $subjects) {
                foreach ($subjects as $s) {
                    $createSubjectAndAdd(strtoupper($s['code']) . '-G' . $g, $s['name'], $gradeLabel . ' - ' . $streamName);
                }
            }
            foreach ($alCommon as $s) {
                $createSubjectAndAdd(strtoupper($s['code']) . '-G' . $g, $s['name'], $gradeLabel);
            }
            $extras = [
                ['code'=>'COMMUN','name'=>'Communication & Media'],
                ['code'=>'DRAMA','name'=>'Drama/Music/Dance'],
                ['code'=>'CSTM','name'=>'Computer Studies'],
            ];
            foreach ($extras as $s) {
                $createSubjectAndAdd(strtoupper($s['code']) . '-G' . $g, $s['name'], $gradeLabel);
            }
        }

        Model::reguard();
        $this->command->info('Seeding completed.');
    }

    /**
     * Create a dummy video file for seeding (MP4 format)
     */
    private function createDummyVideoFile($subjectCode, $topicNum, $subtopicNum)
    {
        $fileName = "dummy-video-{$subjectCode}-T{$topicNum}-S{$subtopicNum}.mp4";
        $filePath = "resources/{$fileName}";

        if (!Storage::disk('public')->exists($filePath)) {
            // Create a minimal valid MP4 file (you can replace with actual video if needed)
            $dummyContent = $this->getMinimalMP4();
            Storage::disk('public')->put($filePath, $dummyContent);
        }

        return $filePath;
    }

    /**
     * Create a dummy image file for seeding (PNG format)
     */
    private function createDummyImageFile($subjectCode, $topicNum, $subtopicNum)
    {
        $fileName = "dummy-image-{$subjectCode}-T{$topicNum}-S{$subtopicNum}.png";
        $filePath = "resources/{$fileName}";

        if (!Storage::disk('public')->exists($filePath)) {
            // Create a simple PNG image (1x1 transparent pixel as placeholder)
            $dummyContent = $this->getMinimalPNG();
            Storage::disk('public')->put($filePath, $dummyContent);
        }

        return $filePath;
    }

    /**
     * Get minimal MP4 file header (placeholder)
     * For production, replace with actual video files
     */
    private function getMinimalMP4()
    {
        // Minimal MP4 header (not a complete video, but valid for file existence)
        return base64_decode(
            'AAAALGZ0eXBpc29tAAACAGlzbW1pc2F3AAAAI21kYXQaaaaaaa='
        );
    }

    /**
     * Get minimal PNG file (1x1 transparent pixel)
     */
    private function getMinimalPNG()
    {
        // 1x1 transparent PNG
        return base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='
        );
    }
}