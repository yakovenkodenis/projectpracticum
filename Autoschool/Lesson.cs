using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Autoschool
{
    public class Lesson
    {
        public string TeacherName { get; set;}
        public string StudentName { get; set; }
        public string Room { get; set; }
        public string MeetPoint { get; set; }
        public string IsReserved { get; set; }
        public string GroupName { get; set; }
        public string StartTime { get; set; }
        public string FinishTime { get; set; }
        public string LessonId { get; set; }

        public override string ToString()
        {
            return "Id: " + LessonId + "\tGroup: " + GroupName + "\tTeacher: " + TeacherName;
        }
    }
}
