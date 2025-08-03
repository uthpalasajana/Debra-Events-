using AutoMapper;
using debraApp.Dto;
using debraApp.Models;

namespace debraApp.Helper
{
    public class MappingProfiles : Profile
    {
        public MappingProfiles()
        {
            CreateMap<Event, EventDto>();
            CreateMap<EventDto, Event>();
            CreateMap<Partner, PartnerDto>();
            CreateMap<PartnerDto, Partner>();
            CreateMap<Ticket, TicketDto>();
            CreateMap<TicketDto, Ticket>();          
            CreateMap<Customer, CustomerDto>();
            CreateMap<CustomerDto, Customer>();
            CreateMap<Sale, SaleDto>();
            CreateMap<SaleDto, Sale>();
            CreateMap<Commission, CommissionDto>();
            CreateMap<CommissionDto, Commission>();
            CreateMap<User, UserDto>();
            CreateMap<UserDto, User>();
        }
    }
}
