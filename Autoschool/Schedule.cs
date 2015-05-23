using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Autoschool
{
    class Schedule
    {
        public string Id { get; set; }
        public string TeacherId { get; set; }
        public string LessonId { get; set; }

        public override string ToString()
        {
            return "TeacherId: " + TeacherId + "\tLessonId: " + LessonId;
        }
    }
}
